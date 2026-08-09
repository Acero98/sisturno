<?php

class AtencionModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerContexto($idUsuario)
    {
        $ticketActual = $this->obtenerTicketActual($idUsuario);
        $tickets = $this->obtenerPendientes($idUsuario);

        if ($ticketActual === null && !empty($tickets)) {
            $ticketActual = [
                'id_tickets' => $tickets[0]['id_tickets'],
                'numero_tk' => $tickets[0]['ticket'],
                'estado_tk' => $tickets[0]['estado'],
                'servicio' => $tickets[0]['servicio'],
            ];
        }

        return [
            'ventanilla' => $this->obtenerVentanilla($idUsuario),
            'ticket' => $ticketActual,
            'tickets' => $tickets,
            'estadisticas' => $this->obtenerEstadisticas($idUsuario),
            'servicios' => $this->obtenerServiciosAtendidos($idUsuario),
        ];
    }

    public function llamarSiguiente($idUsuario)
    {
        $this->conexion->begin_transaction();
        try {
            $fila = $this->unaFila(
                "SELECT t.id_tickets, t.numero_tk
                 FROM tickets t
                 INNER JOIN operador_servicios os ON t.id_servicios = os.id_servicio
                 INNER JOIN servicios s ON t.id_servicios = s.id_servicios
                 WHERE os.id_usuario = ? AND t.estado_tk = 'PENDIENTE' AND t.fecha_tk = CURRENT_DATE
                 ORDER BY CASE s.prioridad_serv WHEN 'EMERGENCIA' THEN 1 WHEN 'ALTA' THEN 2 WHEN 'NORMAL' THEN 3 ELSE 4 END, t.creado_tk ASC
                 LIMIT 1 FOR UPDATE",
                'i',
                [$idUsuario]
            );
            if (!$fila) {
                $this->conexion->rollback();
                return 'SIN_TICKETS';
            }
            $actualizado = $this->actualizar(
                "UPDATE tickets SET estado_tk = 'LLAMADO', id_usuario = ?, hora_cita = CURRENT_TIMESTAMP
                 WHERE id_tickets = ? AND estado_tk = 'PENDIENTE'",
                'ii',
                [$idUsuario, (int) $fila['id_tickets']]
            );
            if ($actualizado !== 1) {
                $this->conexion->rollback();
                return 'ERROR';
            }
            $this->conexion->commit();
            return ['estado' => 'OK', 'ticket' => $fila['numero_tk']];
        } catch (Throwable $error) {
            $this->conexion->rollback();
            return 'ERROR';
        }
    }

    public function comenzarAtencion($idUsuario)
    {
        return $this->cambiarTicketActivo($idUsuario, 'LLAMADO', 'EN_ATENCION', 'hora_atencion = CURRENT_TIMESTAMP');
    }

    public function finalizarAtencion($idUsuario)
    {
        return $this->cambiarTicketActivo($idUsuario, 'EN_ATENCION', 'FINALIZADO', 'hora_finalizado = CURRENT_TIMESTAMP');
    }

    public function cancelarAtencion($idUsuario)
    {
        $this->conexion->begin_transaction();
        try {
            $fila = $this->unaFila(
                "SELECT id_tickets FROM tickets
                 WHERE id_usuario = ? AND estado_tk IN ('LLAMADO', 'EN_ATENCION') AND fecha_tk = CURRENT_DATE
                 ORDER BY creado_tk ASC LIMIT 1 FOR UPDATE",
                'i',
                [$idUsuario]
            );
            if (!$fila) {
                $this->conexion->rollback();
                return 'ERROR';
            }
            $actualizado = $this->actualizar(
                "UPDATE tickets SET estado_tk = 'CANCELADO', hora_finalizado = CURRENT_TIMESTAMP
                 WHERE id_tickets = ? AND id_usuario = ? AND estado_tk IN ('LLAMADO', 'EN_ATENCION')",
                'ii',
                [(int) $fila['id_tickets'], $idUsuario]
            );
            if ($actualizado !== 1) {
                $this->conexion->rollback();
                return 'ERROR';
            }
            $this->conexion->commit();
            return 'OK';
        } catch (Throwable $error) {
            $this->conexion->rollback();
            return 'ERROR';
        }
    }

    private function obtenerVentanilla($idUsuario)
    {
        return $this->unaFila(
            'SELECT num_ventanilla FROM usuarios WHERE id_usuario = ? LIMIT 1',
            'i',
            [$idUsuario]
        ) ?: [];
    }

    private function cambiarTicketActivo($idUsuario, $estadoActual, $estadoNuevo, $campoFecha)
    {
        $this->conexion->begin_transaction();
        try {
            $fila = $this->unaFila(
                "SELECT id_tickets FROM tickets
                 WHERE id_usuario = ? AND estado_tk = ? AND fecha_tk = CURRENT_DATE
                 ORDER BY hora_cita ASC LIMIT 1 FOR UPDATE",
                'is',
                [$idUsuario, $estadoActual]
            );
            if (!$fila) {
                $this->conexion->rollback();
                return 'ERROR';
            }
            $actualizado = $this->actualizar(
                "UPDATE tickets SET estado_tk = ?, $campoFecha
                 WHERE id_tickets = ? AND id_usuario = ? AND estado_tk = ?",
                'siis',
                [$estadoNuevo, (int) $fila['id_tickets'], $idUsuario, $estadoActual]
            );
            if ($actualizado !== 1) {
                $this->conexion->rollback();
                return 'ERROR';
            }
            $this->conexion->commit();
            return 'OK';
        } catch (Throwable $error) {
            $this->conexion->rollback();
            return 'ERROR';
        }
    }

    private function obtenerTicketActual($idUsuario)
    {
        return $this->unaFila(
            "SELECT t.id_tickets, t.numero_tk, t.estado_tk, s.nombre_serv AS servicio
             FROM tickets t
             INNER JOIN servicios s ON t.id_servicios = s.id_servicios
             WHERE t.id_usuario = ?
               AND t.estado_tk IN ('LLAMADO', 'EN_ATENCION')
               AND t.fecha_tk = CURRENT_DATE
             ORDER BY t.creado_tk ASC
             LIMIT 1",
            'i',
            [$idUsuario]
        );
    }

    private function obtenerPendientes($idUsuario)
    {
        $resultado = $this->consulta(
            "SELECT t.id_tickets, t.numero_tk, t.estado_tk, s.nombre_serv
             FROM tickets t
             INNER JOIN operador_servicios os ON t.id_servicios = os.id_servicio
             INNER JOIN servicios s ON t.id_servicios = s.id_servicios
             WHERE os.id_usuario = ?
               AND t.estado_tk = 'PENDIENTE'
               AND t.fecha_tk = CURRENT_DATE
             ORDER BY CASE s.prioridad_serv
                        WHEN 'EMERGENCIA' THEN 1
                        WHEN 'ALTA' THEN 2
                        WHEN 'NORMAL' THEN 3
                        ELSE 4
                      END, t.creado_tk ASC",
            'i',
            [$idUsuario]
        );
        $tickets = [];
        $numero = 1;
        while ($fila = $resultado->fetch_assoc()) {
            $tickets[] = [
                'numero' => $numero++,
                'estado' => $fila['estado_tk'],
                'ticket' => $fila['numero_tk'],
                'servicio' => $fila['nombre_serv'],
                'id_tickets' => $fila['id_tickets'],
            ];
        }
        return $tickets;
    }

    private function obtenerEstadisticas($idUsuario)
    {
        return $this->unaFila(
            "SELECT COUNT(*) AS total_tickets,
                    SUM(estado_tk = 'EN_ATENCION') AS en_atencion,
                    SUM(estado_tk = 'FINALIZADO') AS finalizados,
                    SUM(estado_tk = 'CANCELADO') AS cancelados,
                    AVG(CASE WHEN estado_tk = 'FINALIZADO' THEN TIMESTAMPDIFF(MINUTE, hora_atencion, hora_finalizado) END) AS promedio_atencion,
                    AVG(CASE WHEN hora_atencion IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, hora_cita, hora_atencion) END) AS promedio_espera
             FROM tickets
             WHERE id_usuario = ? AND fecha_tk = CURRENT_DATE",
            'i',
            [$idUsuario]
        ) ?: [];
    }

    private function obtenerServiciosAtendidos($idUsuario)
    {
        return $this->consulta(
            "SELECT s.nombre_serv, COUNT(*) AS cantidad
             FROM tickets t
             INNER JOIN servicios s ON s.id_servicios = t.id_servicios
             WHERE t.id_usuario = ? AND t.fecha_tk = CURRENT_DATE
             GROUP BY s.id_servicios, s.nombre_serv
             ORDER BY cantidad DESC",
            'i',
            [$idUsuario]
        )->fetch_all(MYSQLI_ASSOC);
    }

    private function unaFila($sql, $tipos, $parametros)
    {
        return $this->consulta($sql, $tipos, $parametros)->fetch_assoc();
    }

    private function consulta($sql, $tipos, $parametros)
    {
        $stmt = $this->conexion->prepare($sql);
        $referencias = [$tipos];
        foreach ($parametros as $indice => $valor) {
            $referencias[] = &$parametros[$indice];
        }
        call_user_func_array([$stmt, 'bind_param'], $referencias);
        $stmt->execute();
        return $stmt->get_result();
    }

    private function actualizar($sql, $tipos, $parametros)
    {
        $stmt = $this->conexion->prepare($sql);
        $referencias = [$tipos];
        foreach ($parametros as $indice => $valor) {
            $referencias[] = &$parametros[$indice];
        }
        call_user_func_array([$stmt, 'bind_param'], $referencias);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
