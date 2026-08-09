<?php

class PantallaTurnosModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTurnosActivos($limite = 6)
    {
        $limite = max(1, min(20, (int) $limite));
        $sql = "SELECT t.numero_tk, t.estado_tk, s.nombre_serv, s.prioridad_serv, u.num_ventanilla
                FROM tickets t
                INNER JOIN servicios s ON t.id_servicios = s.id_servicios
                LEFT JOIN usuarios u ON t.id_usuario = u.id_usuario
                WHERE t.estado_tk IN ('PENDIENTE', 'LLAMADO', 'EN_ATENCION')
                  AND t.fecha_tk = CURRENT_DATE
                ORDER BY CASE t.estado_tk
                            WHEN 'LLAMADO' THEN 1
                            WHEN 'EN_ATENCION' THEN 2
                            WHEN 'PENDIENTE' THEN 3
                            ELSE 4
                         END,
                         CASE s.prioridad_serv
                            WHEN 'EMERGENCIA' THEN 1
                            WHEN 'ALTA' THEN 2
                            WHEN 'NORMAL' THEN 3
                            ELSE 4
                         END,
                         t.creado_tk ASC
                LIMIT $limite";
        $resultado = $this->conexion->query($sql);

        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
