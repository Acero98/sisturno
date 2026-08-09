<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/modelo/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$ruta = $_GET['ruta'] ?? '';

switch ($ruta) {

    case 'usuarios':

        require_once __DIR__ . '/app/Controllers/UsuarioController.php';

        $controller = new UsuarioController($conexion);

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'registrar':
                $controller->registrar();
                break;

            case 'actualizar':
                $controller->actualizar();
                break;

            case 'estado':
                $controller->cambiarEstado();
                break;

            default:
                $controller->index();
                break;
        }

        break;

    case 'operadores':

        require_once __DIR__ . '/app/Controllers/OperadorController.php';

        $controller = new OperadorController($conexion);
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'registrar': $controller->registrar(); break;
            case 'actualizar': $controller->actualizar(); break;
            case 'estado': $controller->cambiarEstado(); break;
            default: $controller->index(); break;
        }

        break;

    case 'operadores-servicios':

        require_once __DIR__ . '/app/Controllers/OperadorServicioController.php';

        $controller = new OperadorServicioController($conexion);

        if (($_GET['action'] ?? 'index') === 'guardar') {
            $controller->guardar();
        } else {
            $controller->index();
        }

        break;

    case 'servicios':

        require_once __DIR__ . '/app/Controllers/ServicioController.php';

        $controller = new ServicioController($conexion);
        $action = $_GET['action'] ?? 'index';

        if ($action === 'registrar') {
            $controller->registrar();
        } elseif ($action === 'actualizar') {
            $controller->actualizar();
        } elseif ($action === 'estado') {
            $controller->cambiarEstado();
        } else {
            $controller->index();
        }

        break;

    case 'reporte-general':

        require_once __DIR__ . '/app/Controllers/ReporteGeneralController.php';
        $controller = new ReporteGeneralController($conexion);
        $controller->index();

        break;

    default:

        require_once __DIR__ . '/app/Controllers/InicioController.php';

        $controller = new InicioController($conexion);
        $controller->index();

        break;
}
