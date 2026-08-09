<?php

class InicioModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // =====================================================
    // MÉTRICAS
    // =====================================================

    /**
     * Total de tickets generados hoy
     */
    public function obtenerTotal()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM tickets
            WHERE DATE(fecha_tk) = CURDATE()
        ";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_object()->total ?? 0;
    }


    /**
     * Tickets pendientes hoy
     */
    public function obtenerPendientes()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM tickets
            WHERE estado_tk = 'PENDIENTE'
            AND DATE(fecha_tk) = CURDATE()
        ";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_object()->total ?? 0;
    }


    /**
     * Tickets llamados hoy
     */
    public function obtenerLlamados()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM tickets
            WHERE estado_tk = 'LLAMADO'
            AND DATE(fecha_tk) = CURDATE()
        ";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_object()->total ?? 0;
    }


    /**
     * Tickets en atención hoy
     */
    public function obtenerEnAtencion()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM tickets
            WHERE estado_tk = 'EN_ATENCION'
            AND DATE(fecha_tk) = CURDATE()
        ";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_object()->total ?? 0;
    }


    /**
     * Tickets finalizados hoy
     */
    public function obtenerAtendidos()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM tickets
            WHERE estado_tk = 'FINALIZADO'
            AND DATE(fecha_tk) = CURDATE()
        ";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_object()->total ?? 0;
    }


    /**
     * Tickets cancelados hoy
     */
    public function obtenerCancelados()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM tickets
            WHERE estado_tk = 'CANCELADO'
            AND DATE(fecha_tk) = CURDATE()
        ";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_object()->total ?? 0;
    }


    // =====================================================
    // TIEMPOS
    // =====================================================

    /**
     * Tiempo promedio de espera
     *
     * creado_tk → hora_cita
     */
    public function obtenerPromedioEspera()
    {
        $sql = "
            SELECT
                AVG(
                    TIMESTAMPDIFF(
                        MINUTE,
                        creado_tk,
                        hora_cita
                    )
                ) AS promedio_espera

            FROM tickets

            WHERE estado_tk IN (
                'LLAMADO',
                'EN_ATENCION',
                'FINALIZADO'
            )

            AND DATE(fecha_tk) = CURDATE()

            AND hora_cita IS NOT NULL
        ";

        $resultado = $this->conexion->query($sql);

        if (!$resultado) {
            return 0;
        }

        $row = $resultado->fetch_assoc();

        return $row['promedio_espera'] !== null
            ? round($row['promedio_espera'])
            : 0;
    }


    /**
     * Tiempo promedio de atención
     *
     * hora_atencion → hora_finalizado
     */
    public function obtenerPromedioAtencion()
    {
        $sql = "
            SELECT
                AVG(
                    TIMESTAMPDIFF(
                        MINUTE,
                        hora_atencion,
                        hora_finalizado
                    )
                ) AS promedio_atencion

            FROM tickets

            WHERE estado_tk = 'FINALIZADO'

            AND DATE(fecha_tk) = CURDATE()

            AND hora_atencion IS NOT NULL

            AND hora_finalizado IS NOT NULL
        ";

        $resultado = $this->conexion->query($sql);

        if (!$resultado) {
            return 0;
        }

        $row = $resultado->fetch_assoc();

        return $row['promedio_atencion'] !== null
            ? round($row['promedio_atencion'])
            : 0;
    }


    // =====================================================
    // SERVICIOS
    // =====================================================

    /**
     * Top 5 servicios más solicitados
     */
    public function obtenerTopServicios()
    {
        $sql = "
            SELECT
                s.nombre_serv,
                s.codigo_serv,
                COUNT(t.id_tickets) AS total_atenciones

            FROM tickets t

            INNER JOIN servicios s
                ON t.id_servicios = s.id_servicios

            WHERE DATE(t.fecha_tk) = CURDATE()

            GROUP BY
                t.id_servicios,
                s.nombre_serv,
                s.codigo_serv

            ORDER BY total_atenciones DESC

            LIMIT 5
        ";

        $resultado = $this->conexion->query($sql);

        $datos = [];

        if ($resultado) {

            while ($row = $resultado->fetch_assoc()) {
                $datos[] = $row;
            }
        }

        return $datos;
    }


    // =====================================================
    // RANKING DE OPERADORES
    // =====================================================

    /**
     * Top 10 operadores con más atenciones
     */
    public function obtenerRankingOperadores()
    {
        $sql = "
            SELECT
                u.nombre_user,
                COUNT(t.id_tickets) AS total_atenciones,

                AVG(
                    TIMESTAMPDIFF(
                        MINUTE,
                        t.hora_atencion,
                        t.hora_finalizado
                    )
                ) AS promedio_atencion

            FROM tickets t

            INNER JOIN usuarios u
                ON t.id_usuario = u.id_usuario

            WHERE t.estado_tk = 'FINALIZADO'

            AND DATE(t.fecha_tk) = CURDATE()

            AND t.hora_atencion IS NOT NULL

            AND t.hora_finalizado IS NOT NULL

            GROUP BY
                u.id_usuario,
                u.nombre_user

            ORDER BY total_atenciones DESC

            LIMIT 10
        ";

        return $this->conexion->query($sql);
    }


    // =====================================================
    // TENDENCIA SEMANAL
    // =====================================================

    /**
     * Tickets de los últimos 7 días
     */
    public function obtenerTendencia()
    {
        $sql = "
            SELECT
                DATE(fecha_tk) AS fecha,
                COUNT(*) AS total

            FROM tickets

            WHERE fecha_tk >= DATE_SUB(
                CURDATE(),
                INTERVAL 6 DAY
            )

            GROUP BY DATE(fecha_tk)

            ORDER BY fecha ASC
        ";

        $resultado = $this->conexion->query($sql);

        $datos = [];

        if ($resultado) {

            while ($row = $resultado->fetch_assoc()) {

                $datos[] = [
                    'fecha' => date(
                        'd/m',
                        strtotime($row['fecha'])
                    ),
                    'total' => (int) $row['total']
                ];
            }
        }

        return $datos;
    }


    // =====================================================
    // HORAS PICO
    // =====================================================

    /**
     * Tickets agrupados por hora
     */
    public function obtenerHorasPico()
    {
        $sql = "
            SELECT
                HOUR(creado_tk) AS hora,
                COUNT(*) AS total

            FROM tickets

            WHERE DATE(fecha_tk) = CURDATE()

            GROUP BY HOUR(creado_tk)

            ORDER BY hora ASC
        ";

        $resultado = $this->conexion->query($sql);

        $datos = [];

        if ($resultado) {

            while ($row = $resultado->fetch_assoc()) {

                $horaFormateada =
                    str_pad(
                        $row['hora'],
                        2,
                        '0',
                        STR_PAD_LEFT
                    ) . ':00';

                $datos[] = [
                    'hora' => $horaFormateada,
                    'total' => (int) $row['total']
                ];
            }
        }

        return $datos;
    }

    public function obtenerUsuario($idUsuario)
    {
        $sql = "
            SELECT *
            FROM usuarios
            WHERE id_usuario = ?
            LIMIT 1
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        return $stmt->get_result()->fetch_object();
    }
}
