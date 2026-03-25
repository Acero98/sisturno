<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include "vista/header.php";
include "modelo/conexion.php";

include "control/auth.php";
include "control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

// MÉTRICAS
$total = $conexion->query("SELECT COUNT(*) as total FROM tickets")->fetch_object()->total;

$pendientes = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado_tk='PENDIENTE'")->fetch_object()->total;

$atendidos = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado_tk='ATENDIDO'")->fetch_object()->total;

$cancelados = $conexion->query("SELECT COUNT(*) as total FROM tickets WHERE estado_tk='CANCELADO'")->fetch_object()->total;

?>

<div class="container py-4">

    <!-- Bienvenida -->
    <div class="card card-dashboard shadow-sm mb-4 p-4">
        <h4>Bienvenido Administrador,</h4>
        <span class="text-primary fw-bold">ADMIN</span>
        <div class="mt-2 text-muted">
            <i class="fa-solid fa-circle text-success"></i> 20/02/2026
        </div>
    </div>

    <!-- Cards estadísticas -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card card-dashboard shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-azul me-3">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <small class="text-muted">ATENCIONES TOTALES</small>
                        <h3>103</h3>
                    </div>
                </div>
                <hr>
                <small class="text-muted">Actualizado a 03:25 p.m.</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-dashboard shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-gris me-3">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <small class="text-muted">EN ESPERA</small>
                        <h3>95</h3>
                    </div>
                </div>
                <hr>
                <small class="text-muted">Actualizado a 03:25 p.m.</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-dashboard shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-verde me-3">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <small class="text-muted">COMPLETADAS</small>
                        <h3>08</h3>
                    </div>
                </div>
                <hr>
                <small class="text-muted">Actualizado a 03:25 p.m.</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-dashboard shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-rojo me-3">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <div>
                        <small class="text-muted">CANCELADAS</small>
                        <h3>00</h3>
                    </div>
                </div>
                <hr>
                <small class="text-muted">Actualizado a 03:25 p.m.</small>
            </div>
        </div>

    </div>

    <!-- Tablas -->
    <div class="row g-4">

        <!-- Top procesos -->
        <div class="col-md-6">
            <div class="card card-dashboard shadow-sm p-4">
                <h5>TOP PROCESOS <small class="text-muted">(MAS USADOS)</small></h5>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>NOMBRES</th>
                            <th>CÓDIGO</th>
                            <th>ATENCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>RECLAMO COM.</td>
                            <td>RECO</td>
                            <td>91</td>
                        </tr>
                        <tr>
                            <td>CONEXIONES NUEVAS</td>
                            <td>CONN</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>OTROS</td>
                            <td>OTRO</td>
                            <td>2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top empleados -->
        <div class="col-md-6">
            <div class="card card-dashboard shadow-sm p-4">
                <h5>TOP EMPLEADOS <small class="text-muted">(CON MÁS ATENCIONES)</small></h5>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>NOMBRES</th>
                            <th>CÓDIGO</th>
                            <th>ATENCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MARIA ANELA VICTORIA BAYARRI FERNANDEZ</td>
                            <td>B001</td>
                            <td>8</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<?php
include "vista/footer.php";
?>