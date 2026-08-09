<?php

class UsuarioModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Obtener usuarios paginados
     */
    public function obtenerUsuarios($buscar = '', $inicio = 0, $limite = 10)
    {
        $sql = "
            SELECT 
                u.*,
                r.nombre_rol
            FROM usuarios u
            INNER JOIN roles r 
                ON u.id_rol_user = r.id_rol
        ";

        $parametros = [];
        $tipos = "";

        if (!empty($buscar)) {

            $sql .= "
                WHERE 
                    u.nombre_user LIKE ?
                    OR u.usuario_user LIKE ?
                    OR r.nombre_rol LIKE ?
                    OR u.estado_user LIKE ?
            ";

            $buscarLike = "%" . $buscar . "%";

            $parametros = [
                $buscarLike,
                $buscarLike,
                $buscarLike,
                $buscarLike
            ];

            $tipos = "ssss";
        }

        $sql .= "
            ORDER BY u.nombre_user ASC
            LIMIT ?, ?
        ";

        $parametros[] = $inicio;
        $parametros[] = $limite;
        $tipos .= "ii";

        $stmt = $this->conexion->prepare($sql);

        if (!empty($parametros)) {
            $stmt->bind_param($tipos, ...$parametros);
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    public function obtenerTodos()
    {
        $sql = 'SELECT u.*, r.nombre_rol FROM usuarios u INNER JOIN roles r ON u.id_rol_user = r.id_rol ORDER BY u.nombre_user ASC';
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Contar usuarios
     */
    public function contarUsuarios($buscar = '')
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM usuarios u
            INNER JOIN roles r 
                ON r.id_rol = u.id_rol_user
        ";

        if (!empty($buscar)) {

            $sql .= "
                WHERE 
                    u.nombre_user LIKE ?
                    OR u.usuario_user LIKE ?
                    OR r.nombre_rol LIKE ?
                    OR u.estado_user LIKE ?
            ";

            $stmt = $this->conexion->prepare($sql);

            $buscarLike = "%" . $buscar . "%";

            $stmt->bind_param(
                "ssss",
                $buscarLike,
                $buscarLike,
                $buscarLike,
                $buscarLike
            );

        } else {

            $stmt = $this->conexion->prepare($sql);
        }

        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_object();

        return (int) $resultado->total;
    }

    /**
     * Obtener roles activos
     */
    public function obtenerRoles()
    {
        $sql = "
            SELECT 
                id_rol,
                nombre_rol
            FROM roles
            WHERE estado_rol = 1
            ORDER BY nombre_rol ASC
        ";

        return $this->conexion->query($sql);
    }

    /**
     * Verificar si existe usuario
     */
    public function existeUsuario($usuario, $idExcluir = null)
    {
        if ($idExcluir !== null) {

            $sql = "
                SELECT id_usuario
                FROM usuarios
                WHERE usuario_user = ?
                AND id_usuario != ?
            ";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "si",
                $usuario,
                $idExcluir
            );

        } else {

            $sql = "
                SELECT id_usuario
                FROM usuarios
                WHERE usuario_user = ?
            ";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "s",
                $usuario
            );
        }

        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Registrar usuario
     */
    public function registrarUsuario(
        $usuario,
        $password,
        $nombre,
        $rol
    ) {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "
            INSERT INTO usuarios (
                usuario_user,
                password_user,
                nombre_user,
                id_rol_user
            )
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "sssi",
            $usuario,
            $passwordHash,
            $nombre,
            $rol
        );

        return $stmt->execute();
    }

    /**
     * Actualizar usuario
     */
    public function actualizarUsuario(
        $id,
        $nombre,
        $rol,
        $password = ''
    ) {
        if (!empty($password)) {

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "
                UPDATE usuarios
                SET
                    nombre_user = ?,
                    password_user = ?,
                    id_rol_user = ?
                WHERE id_usuario = ?
            ";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "ssii",
                $nombre,
                $passwordHash,
                $rol,
                $id
            );

        } else {

            $sql = "
                UPDATE usuarios
                SET
                    nombre_user = ?,
                    id_rol_user = ?
                WHERE id_usuario = ?
            ";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param(
                "sii",
                $nombre,
                $rol,
                $id
            );
        }

        return $stmt->execute();
    }

    /**
     * Cambiar estado
     */
    public function cambiarEstado($id, $estado)
    {
        $sql = "
            UPDATE usuarios
            SET estado_user = ?
            WHERE id_usuario = ?
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "ii",
            $estado,
            $id
        );

        return $stmt->execute();
    }
}
