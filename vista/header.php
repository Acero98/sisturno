<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../modelo/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$idSesion = $_SESSION["id_usuario"];
$consultaUser = $conexion->query("SELECT * FROM usuarios WHERE id_usuario=$idSesion");
$usuarioData = $consultaUser->fetch_object();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA DE TURNOS</title>
    <!-- CSS only
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">-->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/operadores.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/header.css">

    <!-- <script src="https://kit.fontawesome.com/c40e82f1b2.js" crossorigin="anonymous"></script> -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/fontawesome/css/all.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm navbar-sistema">
        <div class="container-fluid px-4">

            <!-- Logo -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>">
                <div class="brand-icon me-2">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <span class="brand-title">SISTURNOS</span>
                    <small class="d-block brand-subtitle">Sistema de Gestión de Turnos</small>
                </div>
            </a>

            <!-- Botón responsive -->
            <button class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSistema">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú -->
            <div class="collapse navbar-collapse" id="navbarSistema">

                <!-- Menú principal -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">

                    <?php if ($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == "Admin"): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom active"
                                href="<?= BASE_URL ?>">
                                <i class="fa-solid fa-house me-2"></i>Inicio
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == "Admin"): ?>
                        <!-- Gestión -->
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle"
                                href="#"
                                data-bs-toggle="dropdown">
                                <i class="fa-solid fa-gears me-2"></i>Gestión
                            </a>

                            <ul class="dropdown-menu dropdown-menu-custom shadow border-0">
                                <?php if ($_SESSION['rol'] == 'Super Admin'): ?>
                                    <li>
                                        <a class="dropdown-item"
                                            href="<?= BASE_URL ?>vista/registrar_usuario.php">
                                            <i class="fa-solid fa-user me-2 text-primary"></i>Usuarios
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                            href="<?= BASE_URL ?>vista/servicios/">
                                            <i class="fa-solid fa-list me-2 text-primary"></i>Servicios
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <a class="dropdown-item"
                                        href="<?= BASE_URL ?>vista/lista_operadores.php">
                                        <i class="fa-solid fa-id-badge me-2 text-primary"></i>Operadores
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin'): ?>
                        <!-- Atenciones -->
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle"
                                href="#"
                                data-bs-toggle="dropdown">
                                <i class="fa-solid fa-clipboard-list me-2"></i>Atenciones
                            </a>

                            <ul class="dropdown-menu dropdown-menu-custom shadow border-0">
                                <li>
                                    <a class="dropdown-item"
                                        href="<?= BASE_URL ?>vista/atencion/atencion_cliente.php"
                                        target="_blank">
                                        <i class="fa-solid fa-headset me-2 text-primary"></i>Atención al Cliente
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-magnifying-glass me-2 text-primary"></i>Consultas
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-chart-column me-2 text-primary"></i>Reportes
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin' || $_SESSION['rol'] == 'Monitor'): ?>
                        <!-- Pantallas -->
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle"
                                href="#"
                                data-bs-toggle="dropdown">
                                <i class="fa-solid fa-desktop me-2"></i>Pantallas
                            </a>

                            <ul class="dropdown-menu dropdown-menu-custom shadow border-0">
                                <li>
                                    <a class="dropdown-item"
                                        href="<?= BASE_URL ?>vista/pantalla_seleccion.php"
                                        target="_blank">
                                        <i class="fa-solid fa-check-to-slot me-2 text-primary"></i>Sacar Ticket
                                    </a>
                                </li>

                                <?php if ($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin' || $_SESSION['rol'] == 'Monitor' || $_SESSION['rol'] == 'Operador'): ?>
                                    <li>
                                        <a class="dropdown-item"
                                            href="<?= BASE_URL ?>vista/pantalla_turnos.php"
                                            target="_blank">
                                            <i class="fa-solid fa-ticket me-2 text-primary"></i>Ver Turnos
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin'): ?>
                        <!-- Empresa -->
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="#">
                                <i class="fa-solid fa-building me-2"></i>Empresa
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>

                <!-- Usuario logueado -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-user dropdown-toggle d-flex align-items-center"
                            href="#"
                            data-bs-toggle="dropdown">

                            <div class="user-avatar me-2">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div class="d-none d-lg-block">
                                <div class="user-name">
                                    <?= htmlspecialchars($usuarioData->nombre_user) ?>
                                </div>
                                <small class="user-role">
                                    <?= htmlspecialchars($_SESSION['rol']) ?>
                                </small>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom shadow border-0">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa-solid fa-user-gear me-2 text-primary"></i>Mi Perfil
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger"
                                    href="<?= BASE_URL ?>logout.php">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>