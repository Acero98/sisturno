<?php

class ConsultaGeneralModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerMetricas($inicio, $fin)
    {
        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(estado_tk = 'FINALIZADO') AS atendidos,
                    SUM(estado_tk = 'PENDIENTE') AS pendientes,
                    AVG(CASE
                        WHEN creado_tk IS NOT NULL AND hora_cita IS NOT NULL
                        THEN TIMESTAMPDIFF(MINUTE, creado_tk, hora_cita)
                    END) AS promedio_espera
                FROM tickets
                WHERE fecha_tk BETWEEN ? AND ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ss', $inicio, $fin);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerServicios()
    {
        $resultado = $this->conexion->query(
            'SELECT id_servicios, nombre_serv
             FROM servicios
             ORDER BY nombre_serv ASC'
        );

        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerOperadores()
    {
        $resultado = $this->conexion->query(
            'SELECT id_usuario, nombre_user
             FROM usuarios
             ORDER BY nombre_user ASC'
        );

        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function contarTickets($inicio, $fin, $filtros = [])
    {
        [$where, $tipos, $parametros] = $this->construirFiltros($inicio, $fin, $filtros);
        $fila = $this->ejecutar(
            "SELECT COUNT(*) AS total
             FROM tickets t
             LEFT JOIN servicios s ON t.id_servicios = s.id_servicios
             LEFT JOIN usuarios u ON t.id_usuario = u.id_usuario
             $where",
            $tipos,
            $parametros
        )->fetch_assoc();

        return (int) ($fila['total'] ?? 0);
    }

    public function obtenerTickets($inicio, $fin, $filtros, $inicioRegistro, $cantidad, $columnaOrden, $direccionOrden)
    {
        [$where, $tipos, $parametros] = $this->construirFiltros($inicio, $fin, $filtros);
        $inicioRegistro = max(0, (int) $inicioRegistro);
        $cantidad = max(1, min(100, (int) $cantidad));
        $tipos .= 'ii';
        $parametros[] = $inicioRegistro;
        $parametros[] = $cantidad;

        return $this->ejecutar(
            "SELECT t.numero_tk, s.nombre_serv, t.estado_tk, u.nombre_user,
                    t.fecha_tk, t.creado_tk, t.hora_cita, t.hora_atencion, t.hora_finalizado
             FROM tickets t
             LEFT JOIN servicios s ON t.id_servicios = s.id_servicios
             LEFT JOIN usuarios u ON t.id_usuario = u.id_usuario
             $where
             ORDER BY $columnaOrden $direccionOrden, t.id_tickets DESC
             LIMIT ?, ?",
            $tipos,
            $parametros
        )->fetch_all(MYSQLI_ASSOC);
    }

    private function construirFiltros($inicio, $fin, $filtros)
    {
        $condiciones = ['t.fecha_tk BETWEEN ? AND ?'];
        $tipos = 'ss';
        $parametros = [$inicio, $fin];

        if (!empty($filtros['estado'])) {
            $condiciones[] = 't.estado_tk = ?';
            $tipos .= 's';
            $parametros[] = $filtros['estado'];
        }
        if (!empty($filtros['servicio']) && ctype_digit((string) $filtros['servicio'])) {
            $condiciones[] = 't.id_servicios = ?';
            $tipos .= 'i';
            $parametros[] = (int) $filtros['servicio'];
        }
        if (!empty($filtros['operador']) && ctype_digit((string) $filtros['operador'])) {
            $condiciones[] = 't.id_usuario = ?';
            $tipos .= 'i';
            $parametros[] = (int) $filtros['operador'];
        }
        if (!empty($filtros['search'])) {
            $busqueda = '%' . trim($filtros['search']) . '%';
            $condiciones[] = '(t.numero_tk LIKE ? OR s.nombre_serv LIKE ? OR u.nombre_user LIKE ? OR t.estado_tk LIKE ?)';
            $tipos .= 'ssss';
            array_push($parametros, $busqueda, $busqueda, $busqueda, $busqueda);
        }

        return ['WHERE ' . implode(' AND ', $condiciones), $tipos, $parametros];
    }

    private function ejecutar($sql, $tipos, $parametros)
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
}
