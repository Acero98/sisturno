<?php

class OperadorServicioModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerOperador($idUsuario)
    {
        $sql = 'SELECT id_usuario, nombre_user, usuario_user, puesto_user, oficina_user, num_ventanilla
                FROM usuarios WHERE id_usuario = ? AND id_rol_user IN (2, 3)';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerServiciosActivos()
    {
        $sql = 'SELECT id_servicios, nombre_serv, codigo_serv
                FROM servicios WHERE estado_serv = 1 ORDER BY nombre_serv ASC';
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerIdsAsignados($idUsuario)
    {
        $stmt = $this->conexion->prepare('SELECT id_servicio FROM operador_servicios WHERE id_usuario = ?');
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        return array_map('intval', array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'id_servicio'));
    }

    public function guardarAsignaciones($idUsuario, $servicios)
    {
        $servicios = array_values(array_unique(array_map('intval', $servicios)));
        $this->conexion->begin_transaction();

        try {
            if ($servicios) {
                $marcadores = implode(',', array_fill(0, count($servicios), '?'));
                $tipos = str_repeat('i', count($servicios));
                $stmtValidar = $this->conexion->prepare("SELECT COUNT(*) AS total FROM servicios WHERE estado_serv = 1 AND id_servicios IN ($marcadores)");
                $stmtValidar->bind_param($tipos, ...$servicios);
                $stmtValidar->execute();
                $validos = (int) $stmtValidar->get_result()->fetch_assoc()['total'];
                if ($validos !== count($servicios)) {
                    throw new RuntimeException('Servicio no válido.');
                }
            }

            $stmtEliminar = $this->conexion->prepare('DELETE FROM operador_servicios WHERE id_usuario = ?');
            $stmtEliminar->bind_param('i', $idUsuario);
            $stmtEliminar->execute();

            if ($servicios) {
                $stmtInsertar = $this->conexion->prepare('INSERT INTO operador_servicios (id_usuario, id_servicio) VALUES (?, ?)');
                foreach ($servicios as $idServicio) {
                    $stmtInsertar->bind_param('ii', $idUsuario, $idServicio);
                    $stmtInsertar->execute();
                }
            }

            $this->conexion->commit();
            return true;
        } catch (Throwable $error) {
            $this->conexion->rollback();
            return false;
        }
    }
}
