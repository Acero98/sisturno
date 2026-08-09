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
$action = $_GET['action'] ?? 'index';
$rolActual = $_SESSION['rol'] ?? '';
$rutasPorRol = [
    'Super Admin' => ['usuarios', 'operadores', 'operadores-servicios', 'servicios', 'reporte-general', 'consulta-general', 'atencion', 'seleccion', 'pantalla-turnos'],
    'Admin' => ['usuarios', 'operadores', 'operadores-servicios', 'servicios', 'reporte-general', 'consulta-general', 'atencion', 'seleccion', 'pantalla-turnos'],
    'Operador' => ['atencion', 'pantalla-turnos'],
    'Monitor' => ['seleccion', 'pantalla-turnos'],
    'Turnos' => ['seleccion', 'pantalla-turnos'],
];
$rutaInicialPorRol = [
    'Operador' => 'atencion',
    'Monitor' => 'seleccion',
    'Turnos' => 'pantalla-turnos',
];

if ($ruta === '' && isset($rutaInicialPorRol[$rolActual])) {
    header('Location: ' . BASE_URL . 'index.php?ruta=' . $rutaInicialPorRol[$rolActual]);
    exit();
}

if ($ruta !== '' && !in_array($ruta, $rutasPorRol[$rolActual] ?? [], true)) {
    $destino = $rutaInicialPorRol[$rolActual] ?? '';
    header('Location: ' . BASE_URL . 'index.php' . ($destino !== '' ? '?ruta=' . $destino : ''));
    exit();
}

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

    case 'consulta-general':

        require_once __DIR__ . '/app/Controllers/ConsultaGeneralController.php';
        $controller = new ConsultaGeneralController($conexion);

        if ($action === 'datos') {
            $controller->datos();
        } else {
            $controller->index();
        }

        break;

    case 'atencion':

        require_once __DIR__ . '/app/Controllers/AtencionController.php';
        $controller = new AtencionController($conexion);

        switch ($action) {
            case 'contenido':
                $controller->contenido();
                break;
            case 'llamar':
                $controller->llamar();
                break;
            case 'comenzar':
                $controller->comenzar();
                break;
            case 'finalizar':
                $controller->finalizar();
                break;
            case 'cancelar':
                $controller->cancelar();
                break;
            default:
                $controller->index();
                break;
        }

        break;

    case 'seleccion':

        require_once __DIR__ . '/app/Controllers/SeleccionController.php';
        $controller = new SeleccionController($conexion);

        if ($action === 'contenido') {
            $controller->contenido();
        } elseif ($action === 'generar-ticket') {
            $controller->generarTicket();
        } else {
            $controller->index();
        }

        break;

    case 'pantalla-turnos':

        require_once __DIR__ . '/app/Controllers/PantallaTurnosController.php';
        $controller = new PantallaTurnosController($conexion);

        if ($action === 'contenido') {
            $controller->contenido();
        } else {
            $controller->index();
        }

        break;

    default:

        require_once __DIR__ . '/app/Controllers/InicioController.php';

        $controller = new InicioController($conexion);
        $controller->index();

        break;
}
