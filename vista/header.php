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
    <title>INTENTO DE SISTEMA DE TURNOS</title>
    <!-- CSS only-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c40e82f1b2.js" crossorigin="anonymous"></script>
</head>
<body>
        
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">

            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="#">
            <i class="fa-solid fa-layer-group me-2"></i>SISCOLAS
            </a>

            <!-- Botón responsive -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSistema">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSistema">

            <!-- Menú principal -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php if($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == "Admin"): ?>
                <li class="nav-item">
                <a class="nav-link active" href="<?= BASE_URL ?>index.php">
                    <i class="fa-solid fa-house me-1"></i>Inicio
                </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == "Admin"): ?>
                <!-- Gestión -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-gears me-1"></i>Gestión
                    </a>
                
                    <ul class="dropdown-menu">
                        <?php if($_SESSION['rol'] == 'Super Admin'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>vista/registrar_usuario.php">
                            <i class="fa-solid fa-user me-2"></i>Usuarios
                            </a>
                        </li>

                        <li><a class="dropdown-item" href="<?= BASE_URL ?>vista/servicios/">
                        <i class="fa-solid fa-list me-2"></i>Servicios
                        </a></li>

                        <?php endif; ?>
                        
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>vista/lista_operadores.php">
                        <i class="fa-solid fa-id-badge me-2"></i>Operadores
                        </a></li>
                        
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin'): ?>
                <!-- Atenciones -->
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-clipboard-list me-1"></i>Atenciones
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>vista/atencion/atencion_cliente.php" target="_blank">
                    <i class="fa-solid fa-headset me-2"></i>Atencion al Cliente
                    </a></li>

                    <li><a class="dropdown-item" href="#">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Consultas
                    </a></li>

                    <li><a class="dropdown-item" href="#">
                    <i class="fa-solid fa-chart-column me-2"></i>Reportes
                    </a></li>
                </ul>
                </li>
                <?php endif; ?>
                
                <?php if($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin' || $_SESSION['rol'] == 'Monitor'): ?>
                <!-- Atenciones -->
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-desktop me-1"></i>Pantallas
                </a>
                <ul class="dropdown-menu">                    
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>vista/pantalla_seleccion.php" target="_blank">
                    <i class="fa-solid fa-check-to-slot me-2"></i>Seleccion
                    </a></li>

                    <?php if($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin' || $_SESSION['rol'] == 'Monitor' || $_SESSION['rol'] == 'Operador'): ?>

                    <li><a class="dropdown-item" href="<?= BASE_URL ?>vista/pantalla_turnos.php" target="_blank">
                    <i class="fa-solid fa-ticket me-2"></i>Turnos
                    </a></li>

                    <?php endif; ?>

                </ul>
                </li>
                <?php endif; ?>
                
                <?php if($_SESSION['rol'] == 'Super Admin' || $_SESSION['rol'] == 'Admin'): ?>
                <!-- Empresa -->
                <li class="nav-item">
                <a class="nav-link" href="empresa.php">
                    <i class="fa-solid fa-building me-1"></i>Empresa
                </a>
                </li>
                <?php endif; ?>

            </ul>

            <!-- Usuario logueado -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    <?= $usuarioData->nombre_user ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="perfil.php">
                    <i class="fa-solid fa-user-gear me-2"></i>Perfil
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout.php">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar sesión
                    </a></li>
                </ul>
                </li>
            </ul>

            </div>
        </div>
    </nav>