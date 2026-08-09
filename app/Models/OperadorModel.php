<?php

class OperadorModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function contarOperadores($buscar = '')
    {
        $sql = 'SELECT COUNT(*) AS total
                FROM usuarios u
                INNER JOIN roles r ON r.id_rol = u.id_rol_user
                WHERE u.id_rol_user IN (2, 3)';

        $parametros = [];
        $tipos = '';
        $this->agregarFiltro($sql, $parametros, $tipos, $buscar);

        $stmt = $this->conexion->prepare($sql);
        if ($tipos !== '') {
            $stmt->bind_param($tipos, ...$parametros);
        }
        $stmt->execute();

        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function obtenerOperadores($buscar, $inicio, $limite)
    {
        $sql = 'SELECT
                    u.*, r.nombre_rol,
                    (
                        SELECT GROUP_CONCAT(s.nombre_serv ORDER BY s.nombre_serv SEPARATOR \'|\')
                        FROM operador_servicios os
                        INNER JOIN servicios s ON s.id_servicios = os.id_servicio
                        WHERE os.id_usuario = u.id_usuario
                    ) AS servicios_asignados
                FROM usuarios u
                INNER JOIN roles r ON r.id_rol = u.id_rol_user
                WHERE u.id_rol_user IN (2, 3)';

        $parametros = [];
        $tipos = '';
        $this->agregarFiltro($sql, $parametros, $tipos, $buscar);

        $sql .= ' ORDER BY u.nombre_user ASC
                  LIMIT ?, ?';
        $parametros[] = $inicio;
        $parametros[] = $limite;
        $tipos .= 'ii';

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($tipos, ...$parametros);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTodos()
    {
        return $this->obtenerOperadores('', 0, 100000);
    }

    public function obtenerRolesOperador()
    {
        $sql = 'SELECT id_rol, nombre_rol
                FROM roles
                WHERE id_rol IN (2, 3) AND estado_rol = 1
                ORDER BY nombre_rol ASC';

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function existeUsuario($usuario, $idExcluir = null)
    {
        $sql = 'SELECT id_usuario FROM usuarios WHERE usuario_user = ?';
        $tipos = 's';
        $parametros = [$usuario];

        if ($idExcluir !== null) {
            $sql .= ' AND id_usuario != ?';
            $tipos .= 'i';
            $parametros[] = $idExcluir;
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($tipos, ...$parametros);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function existeDni($dni, $idExcluir = null)
    {
        $sql = 'SELECT id_usuario FROM usuarios WHERE dni_user = ?';
        $tipos = 's';
        $parametros = [$dni];

        if ($idExcluir !== null) {
            $sql .= ' AND id_usuario != ?';
            $tipos .= 'i';
            $parametros[] = $idExcluir;
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($tipos, ...$parametros);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function registrarOperador($datos)
    {
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO usuarios
                (usuario_user, password_user, nombre_user, dni_user, genero_user, puesto_user, oficina_user, observaciones_user, id_rol_user, num_ventanilla)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ssssssssis', $datos['usuario'], $passwordHash, $datos['nombre'], $datos['dni'], $datos['genero'], $datos['puesto'], $datos['oficina'], $datos['observaciones'], $datos['rol'], $datos['ventanilla']);
        return $stmt->execute();
    }

    public function actualizarOperador($id, $datos)
    {
        if ($datos['password'] !== '') {
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
            $sql = 'UPDATE usuarios 
                        SET /*usuario_user = ?, */
                            password_user = ?, 
                            nombre_user = ?, 
                            dni_user = ?, 
                            genero_user = ?, 
                            puesto_user = ?, 
                            oficina_user = ?, 
                            observaciones_user = ?, 
                            id_rol_user = ?, 
                            num_ventanilla = ? 
                        WHERE id_usuario = ? 
                        AND id_rol_user 
                        IN (2, 3)';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                'sssssssisi',
                //$datos['usuario'],
                $passwordHash,
                $datos['nombre'],
                $datos['dni'],
                $datos['genero'],
                $datos['puesto'],
                $datos['oficina'],
                $datos['observaciones'],
                $datos['rol'],
                $datos['ventanilla'],
                $id
            );
        } else {
            $sql = 'UPDATE usuarios 
                        SET /*usuario_user = ?, */
                            nombre_user = ?, 
                            dni_user = ?, 
                            genero_user = ?, 
                            puesto_user = ?, 
                            oficina_user = ?, 
                            observaciones_user = ?, 
                            id_rol_user = ?, 
                            num_ventanilla = ? 
                        WHERE id_usuario = ? 
                        AND id_rol_user 
                        IN (2, 3)';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                'ssssssisi',
                //$datos['usuario'],
                $datos['nombre'],
                $datos['dni'],
                $datos['genero'],
                $datos['puesto'],
                $datos['oficina'],
                $datos['observaciones'],
                $datos['rol'],
                $datos['ventanilla'],
                $id
            );
        }
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado)
    {
        $sql = 'UPDATE usuarios SET estado_user = ? WHERE id_usuario = ? AND id_rol_user IN (2, 3)';
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ii', $estado, $id);
        return $stmt->execute();
    }

    private function agregarFiltro(&$sql, &$parametros, &$tipos, $buscar)
    {
        if ($buscar === '') {
            return;
        }

        $sql .= ' AND (
                    u.nombre_user LIKE ? OR u.usuario_user LIKE ? OR r.nombre_rol LIKE ?
                    OR u.dni_user LIKE ? OR u.puesto_user LIKE ? OR u.oficina_user LIKE ?
                    OR u.num_ventanilla LIKE ?
                    OR (LOWER(?) = "activo" AND u.estado_user = 1)
                    OR (LOWER(?) = "inactivo" AND u.estado_user = 0)
                )';

        $buscarLike = '%' . $buscar . '%';
        $parametros = array_merge(
            $parametros,
            [$buscarLike, $buscarLike, $buscarLike, $buscarLike, $buscarLike, $buscarLike, $buscarLike, $buscar, $buscar]
        );
        $tipos .= 'sssssssss';
    }
}
