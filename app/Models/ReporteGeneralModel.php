<?php

class ReporteGeneralModel
{
    private $conexion;
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerMetricas($inicio, $fin)
    {
        $sql = "SELECT COUNT(*) total, SUM(estado_tk = 'PENDIENTE') pendientes, SUM(estado_tk = 'LLAMADO') llamados, SUM(estado_tk = 'EN_ATENCION') en_atencion, SUM(estado_tk = 'FINALIZADO') atendidos, SUM(estado_tk = 'CANCELADO') cancelados FROM tickets WHERE fecha_tk BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ss', $inicio, $fin);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerEstados($inicio, $fin)
    {
        return $this->consulta('SELECT estado_tk etiqueta, COUNT(*) total FROM tickets WHERE fecha_tk BETWEEN ? AND ? GROUP BY estado_tk ORDER BY total DESC', $inicio, $fin);
    }

    public function obtenerServicios($inicio, $fin)
    {
        return $this->consulta('SELECT s.nombre_serv etiqueta, s.codigo_serv, COUNT(t.id_tickets) total FROM tickets t INNER JOIN servicios s ON t.id_servicios = s.id_servicios WHERE t.fecha_tk BETWEEN ? AND ? GROUP BY t.id_servicios, s.nombre_serv, s.codigo_serv ORDER BY total DESC LIMIT 5', $inicio, $fin);
    }

    public function obtenerRankingOperadores($inicio, $fin)
    {
        return $this->consulta('SELECT u.nombre_user, u.dni_user, 
        COUNT(t.id_tickets) tickets, 
        SUM(t.estado_tk = "FINALIZADO") finalizados, 
        SUM(t.estado_tk = "CANCELADO") cancelados, 
        AVG(CASE WHEN t.estado_tk = "FINALIZADO" THEN TIMESTAMPDIFF(MINUTE, t.hora_atencion, t.hora_finalizado) END) promedio_atencion 
        FROM tickets t 
        INNER JOIN usuarios u 
        ON t.id_usuario = u.id_usuario 
        WHERE t.fecha_tk BETWEEN ? AND ? 
        GROUP BY u.id_usuario, u.nombre_user, u.dni_user 
        ORDER BY finalizados DESC, tickets DESC LIMIT 10', $inicio, $fin);
    }

    public function obtenerTiempoPorServicio($inicio, $fin)
    {
        return $this->consulta('SELECT s.nombre_serv, s.codigo_serv, COUNT(t.id_tickets) finalizados, AVG(TIMESTAMPDIFF(MINUTE, t.hora_atencion, t.hora_finalizado)) promedio_atencion FROM tickets t INNER JOIN servicios s ON t.id_servicios = s.id_servicios WHERE t.estado_tk = "FINALIZADO" AND t.fecha_tk BETWEEN ? AND ? AND t.hora_atencion IS NOT NULL AND t.hora_finalizado IS NOT NULL GROUP BY s.id_servicios, s.nombre_serv, s.codigo_serv ORDER BY promedio_atencion DESC', $inicio, $fin);
    }

    public function obtenerTendencia($inicio, $fin)
    {
        $fechaInicio = new DateTimeImmutable($inicio);
        $fechaFin = new DateTimeImmutable($fin);
        $dias = $fechaInicio->diff($fechaFin)->days + 1;

        if ($dias <= 30) {
            $agrupacion = 'diaria';
            $sql = 'SELECT DATE(fecha_tk) clave, COUNT(*) total
                    FROM tickets
                    WHERE fecha_tk BETWEEN ? AND ?
                    GROUP BY DATE(fecha_tk)
                    ORDER BY clave';
        } elseif ($dias <= 180) {
            $agrupacion = 'semanal';
            $sql = 'SELECT DATE_SUB(DATE(fecha_tk), INTERVAL WEEKDAY(fecha_tk) DAY) clave, COUNT(*) total
                    FROM tickets
                    WHERE fecha_tk BETWEEN ? AND ?
                    GROUP BY DATE_SUB(DATE(fecha_tk), INTERVAL WEEKDAY(fecha_tk) DAY)
                    ORDER BY clave';
        } elseif ($dias <= 730) {
            $agrupacion = 'mensual';
            $sql = "SELECT DATE_FORMAT(fecha_tk, '%Y-%m-01') clave, COUNT(*) total
                    FROM tickets
                    WHERE fecha_tk BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(fecha_tk, '%Y-%m-01')
                    ORDER BY clave";
        } else {
            $agrupacion = 'anual';
            $sql = "SELECT DATE_FORMAT(fecha_tk, '%Y-01-01') clave, COUNT(*) total
                    FROM tickets
                    WHERE fecha_tk BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(fecha_tk, '%Y-01-01')
                    ORDER BY clave";
        }

        $datos = $this->consulta($sql, $inicio, $fin);
        foreach ($datos as &$dato) {
            $dato['etiqueta'] = $this->formatearPeriodo(
                $dato['clave'],
                $agrupacion,
                $fechaInicio,
                $fechaFin
            );
            unset($dato['clave']);
        }
        unset($dato);

        return [
            'agrupacion' => $agrupacion,
            'datos' => $datos,
        ];
    }

    private function formatearPeriodo($clave, $agrupacion, DateTimeImmutable $inicio, DateTimeImmutable $fin)
    {
        $fecha = new DateTimeImmutable($clave);
        $meses = [1 => 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        if ($agrupacion === 'anual') {
            return $fecha->format('Y');
        }

        if ($agrupacion === 'mensual') {
            return $meses[(int) $fecha->format('n')] . ' ' . $fecha->format('Y');
        }

        if ($agrupacion === 'semanal') {
            $inicioSemana = $fecha < $inicio ? $inicio : $fecha;
            $finSemanaCalculado = $fecha->modify('+6 days');
            $finSemana = $finSemanaCalculado > $fin ? $fin : $finSemanaCalculado;

            return $inicioSemana->format('d') . ' ' . $meses[(int) $inicioSemana->format('n')]
                . ' – ' . $finSemana->format('d') . ' ' . $meses[(int) $finSemana->format('n')];
        }

        return $fecha->format('d') . ' ' . $meses[(int) $fecha->format('n')];
    }

    public function obtenerHorasPico($inicio, $fin)
    {
        return $this->consulta('SELECT HOUR(creado_tk) etiqueta, COUNT(*) total FROM tickets WHERE creado_tk BETWEEN ? AND ? GROUP BY HOUR(creado_tk) ORDER BY etiqueta', $inicio . ' 00:00:00', $fin . ' 23:59:59');
    }

    public function obtenerPromedios($inicio, $fin)
    {
        $sql = 'SELECT AVG(TIMESTAMPDIFF(MINUTE, creado_tk, hora_cita)) espera, AVG(TIMESTAMPDIFF(MINUTE, hora_atencion, hora_finalizado)) atencion FROM tickets WHERE fecha_tk BETWEEN ? AND ?';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ss', $inicio, $fin);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function consulta($sql, $inicio, $fin)
    {
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ss', $inicio, $fin);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
