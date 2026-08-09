<?php

class ReporteGeneralModel
{
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function obtenerMetricas($inicio, $fin)
    {
        $sql = "SELECT COUNT(*) total, SUM(estado_tk = 'PENDIENTE') pendientes, SUM(estado_tk = 'LLAMADO') llamados, SUM(estado_tk = 'EN_ATENCION') en_atencion, SUM(estado_tk = 'FINALIZADO') atendidos, SUM(estado_tk = 'CANCELADO') cancelados FROM tickets WHERE fecha_tk BETWEEN ? AND ?";
        $stmt = $this->conexion->prepare($sql); $stmt->bind_param('ss', $inicio, $fin); $stmt->execute();
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
        return $this->consulta('SELECT u.nombre_user, u.dni_user, COUNT(t.id_tickets) total_atenciones, AVG(TIMESTAMPDIFF(MINUTE, t.hora_atencion, t.hora_finalizado)) promedio_atencion FROM tickets t INNER JOIN usuarios u ON t.id_usuario = u.id_usuario WHERE t.estado_tk = "FINALIZADO" AND t.fecha_tk BETWEEN ? AND ? GROUP BY u.id_usuario, u.nombre_user, u.dni_user ORDER BY total_atenciones DESC LIMIT 10', $inicio, $fin);
    }

    public function obtenerTendencia($inicio, $fin)
    {
        return $this->consulta('SELECT DATE(fecha_tk) etiqueta, COUNT(*) total FROM tickets WHERE fecha_tk BETWEEN ? AND ? GROUP BY DATE(fecha_tk) ORDER BY etiqueta', $inicio, $fin);
    }

    public function obtenerHorasPico($inicio, $fin)
    {
        return $this->consulta('SELECT HOUR(creado_tk) etiqueta, COUNT(*) total FROM tickets WHERE creado_tk BETWEEN ? AND ? GROUP BY HOUR(creado_tk) ORDER BY etiqueta', $inicio . ' 00:00:00', $fin . ' 23:59:59');
    }

    public function obtenerPromedios($inicio, $fin)
    {
        $sql = 'SELECT AVG(TIMESTAMPDIFF(MINUTE, creado_tk, hora_cita)) espera, AVG(TIMESTAMPDIFF(MINUTE, hora_atencion, hora_finalizado)) atencion FROM tickets WHERE fecha_tk BETWEEN ? AND ?';
        $stmt = $this->conexion->prepare($sql); $stmt->bind_param('ss', $inicio, $fin); $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function consulta($sql, $inicio, $fin)
    {
        $stmt = $this->conexion->prepare($sql); $stmt->bind_param('ss', $inicio, $fin); $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
