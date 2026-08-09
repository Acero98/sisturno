<?php

class ServicioModel
{
    private $conexion;
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerResumen()
    {
        $sql = "SELECT COUNT(*) total, SUM(estado_serv = 1) activos, SUM(estado_serv = 0) inactivos, SUM(prioridad_serv = 'NORMAL') normal, SUM(prioridad_serv = 'ALTA') alta, SUM(prioridad_serv = 'EMERGENCIA') emergencia FROM servicios";
        return $this->conexion->query($sql)->fetch_assoc();
    }

    public function contar($buscar = '')
    {
        $sql = 'SELECT COUNT(*) total FROM servicios';
        $params = [];
        $tipos = '';
        $this->filtro($sql, $params, $tipos, $buscar);
        $stmt = $this->conexion->prepare($sql);
        if ($tipos) $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function obtener($buscar, $inicio, $limite)
    {
        $sql = 'SELECT * FROM servicios';
        $params = [];
        $tipos = '';
        $this->filtro($sql, $params, $tipos, $buscar);
        $sql .= ' ORDER BY nombre_serv ASC LIMIT ?, ?';
        $params[] = $inicio;
        $params[] = $limite;
        $tipos .= 'ii';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTodos()
    {
        return $this->conexion->query('SELECT * FROM servicios ORDER BY nombre_serv ASC')->fetch_all(MYSQLI_ASSOC);
    }

    public function existeCodigo($codigo, $idExcluir = null)
    {
        $sql = 'SELECT id_servicios FROM servicios WHERE codigo_serv = ?';
        $tipos = 's';
        $params = [$codigo];
        if ($idExcluir !== null) {
            $sql .= ' AND id_servicios != ?';
            $tipos .= 'i';
            $params[] = $idExcluir;
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function registrar($datos)
    {
        $stmt = $this->conexion->prepare('INSERT INTO servicios (nombre_serv, codigo_serv, estado_serv, prioridad_serv, creado_serv) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)');
        $stmt->bind_param('ssis', $datos['nombre'], $datos['codigo'], $datos['estado'], $datos['prioridad']);
        return $stmt->execute();
    }

    public function actualizar($id, $datos)
    {
        $stmt = $this->conexion->prepare('UPDATE servicios SET nombre_serv = ?, codigo_serv = ?, prioridad_serv = ? WHERE id_servicios = ?');
        $stmt->bind_param('sssi', $datos['nombre'], $datos['codigo'], $datos['prioridad'], $id);
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado)
    {
        $stmt = $this->conexion->prepare('UPDATE servicios SET estado_serv = ? WHERE id_servicios = ?');
        $stmt->bind_param('ii', $estado, $id);
        return $stmt->execute();
    }

    private function filtro(&$sql, &$params, &$tipos, $buscar)
    {
        if ($buscar === '') return;
        $sql .= ' WHERE nombre_serv LIKE ? OR codigo_serv LIKE ? OR prioridad_serv LIKE ? OR (LOWER(?) = "activo" AND estado_serv = 1) OR (LOWER(?) = "inactivo" AND estado_serv = 0)';
        $like = '%' . $buscar . '%';
        $params = [$like, $like, $like, $buscar, $buscar];
        $tipos = 'sssss';
    }
}
