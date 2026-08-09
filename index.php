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

    default:

        require_once __DIR__ . '/app/Controllers/InicioController.php';

        $controller = new InicioController($conexion);
        $controller->index();

        break;
}
