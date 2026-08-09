<?php

require_once __DIR__ . '/../Models/ServicioModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class ServicioController
{
    private $model;
    public function __construct($conexion)
    {
        $this->model = new ServicioModel($conexion);
    }

    public function index()
    {
        permitirSolo(['Super Admin', 'Admin']);
        $servicios = $this->model->obtenerTodos();
        $totalRegistros = count($servicios);
        $inicio = 0;
        $pagina = 1;
        $totalPaginas = 1;
        $buscar = '';
        $resumen = $this->model->obtenerResumen();
        $desde = $totalRegistros ? 1 : 0;
        $hasta = $totalRegistros;
        require __DIR__ . '/../Views/Servicios/index.php';
    }

    public function registrar()
    {
        $this->procesarRegistro();
    }
    public function actualizar()
    {
        $this->procesarActualizacion();
    }
    public function cambiarEstado()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirigir('error');
        $id = (int) ($_POST['id'] ?? 0);
        $estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
        if ($id <= 0 || $estado === false || $estado === null) $this->redirigir('error');
        $this->redirigir($this->model->cambiarEstado($id, $estado) ? ($estado ? 'activado' : 'desactivado') : 'error');
    }

    private function procesarRegistro()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirigir('error');
        $datos = $this->datos(true);
        if (!$this->validos($datos, true)) $this->redirigir('obligatorio');
        if ($this->model->existeCodigo($datos['codigo'])) $this->redirigir('existe');
        $this->redirigir($this->model->registrar($datos) ? 'registrado' : 'error');
    }

    private function procesarActualizacion()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirigir('error');
        $id = (int) ($_POST['id'] ?? 0);
        $datos = $this->datos(false);
        if ($id <= 0 || !$this->validos($datos, false)) $this->redirigir('obligatorio');
        if ($this->model->existeCodigo($datos['codigo'], $id)) $this->redirigir('existe');
        $this->redirigir($this->model->actualizar($id, $datos) ? 'actualizado' : 'error');
    }

    private function datos($incluyeEstado)
    {
        $estadoRecibido = $_POST['estado'] ?? null;
        $estado = ($estadoRecibido === '0' || $estadoRecibido === '1') ? (int) $estadoRecibido : -1;
        return ['nombre' => trim($_POST['nombre'] ?? ''), 'codigo' => trim($_POST['codigo'] ?? ''), 'prioridad' => $_POST['prioridad'] ?? '', 'estado' => $incluyeEstado ? $estado : null];
    }
    private function validos($datos, $incluyeEstado)
    {
        return $datos['nombre'] !== '' && $datos['codigo'] !== '' && in_array($datos['prioridad'], ['NORMAL', 'ALTA', 'EMERGENCIA'], true) && (!$incluyeEstado || in_array($datos['estado'], [0, 1], true));
    }
    private function redirigir($mensaje)
    {
        header('Location: ' . BASE_URL . 'index.php?ruta=servicios&mensaje=' . urlencode($mensaje));
        exit();
    }
}
