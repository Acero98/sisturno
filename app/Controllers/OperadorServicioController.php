<?php

require_once __DIR__ . '/../Models/OperadorServicioModel.php';
require_once __DIR__ . '/../../control/permisos.php';

class OperadorServicioController
{
    private $model;

    public function __construct($conexion)
    {
        $this->model = new OperadorServicioModel($conexion);
    }

    public function index()
    {
        permitirSolo(['Super Admin', 'Admin']);
        $idUsuario = (int) ($_GET['id_usuario'] ?? 0);
        $operador = $this->model->obtenerOperador($idUsuario);
        if (!$operador) { $this->volverOperadores('no_encontrado'); }

        $servicios = $this->model->obtenerServiciosActivos();
        $asignados = $this->model->obtenerIdsAsignados($idUsuario);
        require __DIR__ . '/../Views/Operadores/servicios.php';
    }

    public function guardar()
    {
        permitirSolo(['Super Admin', 'Admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->volverOperadores('error'); }
        $idUsuario = (int) ($_POST['id_usuario'] ?? 0);
        if (!$this->model->obtenerOperador($idUsuario)) { $this->volverOperadores('no_encontrado'); }

        $servicios = $_POST['servicios'] ?? [];
        if (!is_array($servicios)) { $servicios = []; }

        $guardado = $this->model->guardarAsignaciones($idUsuario, $servicios);
        $mensaje = $guardado ? 'asignaciones_guardadas' : 'error';
        if ($guardado) {
            require_once __DIR__ . '/../../controlador/atencion/notificar_socket.php';
        }
        header('Location: ' . BASE_URL . 'index.php?ruta=operadores-servicios&id_usuario=' . $idUsuario . '&mensaje=' . $mensaje);
        exit();
    }

    private function volverOperadores($mensaje)
    {
        header('Location: ' . BASE_URL . 'index.php?ruta=operadores&mensaje=' . urlencode($mensaje));
        exit();
    }
}
