<?php

class SeleccionModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerServiciosActivos()
    {
        $resultado = $this->conexion->query(
            'SELECT id_servicios, nombre_serv
             FROM servicios
             WHERE estado_serv = 1
             ORDER BY nombre_serv ASC'
        );

        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function generarTicket($idServicio)
    {
        $idServicio = (int) $idServicio;
        if ($idServicio <= 0) {
            return ['ok' => false, 'error' => 'ERROR'];
        }

        $this->conexion->begin_transaction();
        try {
            $servicio = $this->unaFila(
                'SELECT id_servicios, codigo_serv, prioridad_serv, nombre_serv
                 FROM servicios
                 WHERE id_servicios = ? AND estado_serv = 1
                 LIMIT 1 FOR UPDATE',
                'i',
                [$idServicio]
            );
            if (!$servicio) {
                $this->conexion->rollback();
                return ['ok' => false, 'error' => 'ERROR'];
            }

            $ultimo = $this->unaFila(
                'SELECT numero_tk FROM tickets
                 WHERE fecha_tk = CURRENT_DATE AND id_servicios = ?
                 ORDER BY id_tickets DESC LIMIT 1 FOR UPDATE',
                'i',
                [$idServicio]
            );
            $correlativo = 1;
            if ($ultimo && preg_match('/-(\d+)$/', $ultimo['numero_tk'], $coincidencia)) {
                $correlativo = (int) $coincidencia[1] + 1;
            }

            $ticket = $servicio['codigo_serv'] . '-' . str_pad((string) $correlativo, 3, '0', STR_PAD_LEFT);
            $insertado = $this->actualizar(
                "INSERT INTO tickets (numero_tk, id_servicios, fecha_tk, estado_tk, prioridad_tk, creado_tk)
                 VALUES (?, ?, CURRENT_DATE, 'PENDIENTE', ?, CURRENT_TIMESTAMP)",
                'sis',
                [$ticket, $idServicio, $servicio['prioridad_serv']]
            );
            if ($insertado !== 1) {
                $this->conexion->rollback();
                return ['ok' => false, 'error' => 'ERROR'];
            }

            $this->conexion->commit();
            return ['ok' => true, 'ticket' => $ticket, 'servicio' => $servicio['nombre_serv']];
        } catch (Throwable $error) {
            $this->conexion->rollback();
            return ['ok' => false, 'error' => 'ERROR'];
        }
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
