<?php

require_once __DIR__ . '/../Models/UsuarioModel.php';

class UsuarioController
{
    private $conexion;
    private $usuarioModel;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->usuarioModel = new UsuarioModel($conexion);
    }

    /**
     * Página principal de usuarios
     */
    public function index()
    {
        $registrosPorPagina = 10;

        $pagina = isset($_GET['pagina'])
            ? (int) $_GET['pagina']
            : 1;

        if ($pagina < 1) {
            $pagina = 1;
        }

        $buscar = isset($_GET['buscar'])
            ? trim($_GET['buscar'])
            : '';

        $inicio = ($pagina - 1) * $registrosPorPagina;

        // Total
        $totalRegistros = $this->usuarioModel
            ->contarUsuarios($buscar);

        $totalPaginas = $totalRegistros > 0
            ? ceil($totalRegistros / $registrosPorPagina)
            : 1;

        // Usuarios
        $resultadoUsuarios = $this->usuarioModel
            ->obtenerUsuarios(
                $buscar,
                $inicio,
                $registrosPorPagina
            );
        $usuarios = $resultadoUsuarios->fetch_all(MYSQLI_ASSOC);

        // Roles
        $resultadoRoles = $this->usuarioModel->obtenerRoles();
        $roles = $resultadoRoles->fetch_all(MYSQLI_ASSOC);

        // Información para paginación
        $desde = $totalRegistros > 0
            ? $inicio + 1
            : 0;

        $hasta = min(
            $inicio + $registrosPorPagina,
            $totalRegistros
        );

        require __DIR__ . '/../Views/Usuarios/index.php';
    }

    /**
     * Registrar
     */
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');
        $rol = (int) ($_POST['rol'] ?? 0);

        if (
            empty($usuario) ||
            empty($password) ||
            empty($nombre) ||
            $rol <= 0
        ) {
            $this->redireccionar('obligatorio');
        }

        if ($this->usuarioModel->existeUsuario($usuario)) {
            $this->redireccionar('existe');
        }

        $resultado = $this->usuarioModel->registrarUsuario(
            $usuario,
            $password,
            $nombre,
            $rol
        );

        if ($resultado) {
            $this->redireccionar('registrado');
        }

        $this->redireccionar('error');
    }

    /**
     * Modificar
     */
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $usuario = trim($_POST['usuario'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = (int) ($_POST['rol'] ?? 0);

        if (
            $id <= 0 ||
            empty($usuario) ||
            empty($nombre) ||
            $rol <= 0
        ) {
            $this->redireccionar('obligatorio');
        }

        if (
            $this->usuarioModel
                ->existeUsuario($usuario, $id)
        ) {
            $this->redireccionar('existe');
        }

        $resultado = $this->usuarioModel->actualizarUsuario(
            $id,
            $usuario,
            $nombre,
            $rol,
            $password
        );

        if ($resultado) {
            $this->redireccionar('actualizado');
        }

        $this->redireccionar('error');
    }

    /**
     * Activar / desactivar
     */
    public function cambiarEstado()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redireccionar('error');
        }

        $estado = filter_input(
            INPUT_GET,
            'estado',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 0, 'max_range' => 1]]
        );

        if ($estado === false || $estado === null) {
            $this->redireccionar('error');
        }

        $mensaje = $estado === 1 ? 'activado' : 'desactivado';

        $resultado = $this->usuarioModel
            ->cambiarEstado($id, $estado);

        if ($resultado) {
            $this->redireccionar($mensaje);
        }

        $this->redireccionar('error');
    }

    /**
     * Redirección centralizada
     */
    private function redireccionar($mensaje)
    {
        header(
            'Location: ' .
            BASE_URL .
            'index.php?ruta=usuarios&mensaje=' .
            urlencode($mensaje)
        );

        exit();
    }
}
