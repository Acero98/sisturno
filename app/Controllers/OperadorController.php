<?php

require_once __DIR__ . '/../Models/OperadorModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class OperadorController
{
    private $model;

    public function __construct($conexion)
    {
        $this->model = new OperadorModel($conexion);
    }

    public function index()
    {
        permitirSolo(['Super Admin', 'Admin']);

        $operadores = $this->model->obtenerTodos();
        $totalRegistros = count($operadores);
        $pagina = 1;
        $totalPaginas = 1;
        $inicio = 0;
        $buscar = '';
        $roles = $this->model->obtenerRolesOperador();
        $desde = $totalRegistros === 0 ? 0 : 1;
        $hasta = $totalRegistros;

        require __DIR__ . '/../Views/Operadores/index.php';
    }

    public function registrar()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redireccionar('error');
        }
        $datos = $this->datosFormulario(true);
        if (!$this->datosValidos($datos, true, true)) {
            $this->redireccionar('obligatorio');
        }
        if ($this->model->existeUsuario($datos['usuario'])) {
            $this->redireccionar('usuario_existe');
        }
        if ($this->model->existeDni($datos['dni'])) {
            $this->redireccionar('dni_existe');
        }
        $this->redireccionar($this->model->registrarOperador($datos) ? 'registrado' : 'error');
    }

    public function actualizar()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redireccionar('error');
        }
        $id = (int) ($_POST['id'] ?? 0);
        $datos = $this->datosFormulario();
        if ($id <= 0 || !$this->datosValidos($datos)) {
            $this->redireccionar('obligatorio');
        }
        /*
        if ($this->model->existeUsuario($datos['usuario'], $id)) {
            $this->redireccionar('usuario_existe');
        }*/
        if ($this->model->existeDni($datos['dni'], $id)) {
            $this->redireccionar('dni_existe');
        }
        $this->redireccionar($this->model->actualizarOperador($id, $datos) ? 'actualizado' : 'error');
    }

    public function cambiarEstado()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redireccionar('error');
        }
        $id = (int) ($_POST['id'] ?? 0);
        $estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
        if ($id <= 0 || $estado === false || $estado === null) {
            $this->redireccionar('error');
        }
        $mensaje = $estado === 1 ? 'activado' : 'desactivado';
        $this->redireccionar($this->model->cambiarEstado($id, $estado) ? $mensaje : 'error');
    }

    private function datosFormulario($incluirUsuario = false)
    {
        $datos = [
            'password' => $_POST['password'] ?? '',
            'nombre' => trim($_POST['nombre'] ?? ''),
            'dni' => trim($_POST['dni'] ?? ''),
            'genero' => $_POST['genero'] ?? '',
            'puesto' => trim($_POST['puesto'] ?? ''),
            'oficina' => trim($_POST['oficina'] ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'rol' => (int) ($_POST['rol'] ?? 0),
            'ventanilla' => trim($_POST['ventanilla'] ?? '')
        ];
        if ($incluirUsuario) {
            $datos['usuario'] = trim($_POST['usuario'] ?? '');
        }
        return $datos;
    }

    private function datosValidos($datos, $passwordObligatorio = false, $usuarioObligatorio = false)
    {
        return (!$usuarioObligatorio || (!empty($datos['usuario']))) && $datos['nombre'] !== '' && $datos['dni'] !== '' &&
            in_array($datos['genero'], ['M', 'F'], true) && $datos['puesto'] !== '' &&
            $datos['oficina'] !== '' && $datos['ventanilla'] !== '' && in_array($datos['rol'], [2, 3], true) &&
            (!$passwordObligatorio || $datos['password'] !== '');
    }

    private function redireccionar($mensaje)
    {
        header('Location: ' . BASE_URL . 'index.php?ruta=operadores&mensaje=' . urlencode($mensaje));
        exit();
    }
}
