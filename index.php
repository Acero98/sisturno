<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include "modelo/conexion.php";
include "control/auth.php";
include "control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

include "controlador/dashboard.php";

include "vista/header.php";
?>

<div class="container-fluid py-4">

    <!-- Bienvenida -->
    <div class="welcome-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">
                    <i class="fa-solid fa-gauge-high me-2"></i>
                    Dashboard del Sistema de Turnos
                </h2>
                <p class="mb-1 fs-5">
                    Bienvenido, <strong><?= strtoupper($_SESSION['usuario']) ?></strong>
                </p>
                <small>
                    <i class="fa-solid fa-calendar-days me-1"></i> <?= $fechaActual ?>
                    &nbsp;&nbsp;
                    <i class="fa-solid fa-clock me-1"></i> <?= $horaActual ?>
                </small>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="fa-solid fa-chart-line" style="font-size: 5rem; opacity: 0.15;"></i>
            </div>
        </div>
    </div>

    <!-- Métricas -->
    <div class="row g-4 mb-4">

        <!-- TOTAL DE TICKETS -->
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="metric-card gradient-blue dashboard-card">
                <small>TOTAL DE TICKETS</small>
                <h2><?= number_format($total) ?></h2>
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

        <!-- PENDIENTES -->
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="metric-card gradient-orange dashboard-card">
                <small>PENDIENTES</small>
                <h2><?= number_format($pendientes) ?></h2>
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <!-- LLAMADOS -->
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="metric-card gradient-info dashboard-card">
                <small>LLAMADOS</small>
                <h2><?= number_format($llamados) ?></h2>
                <i class="fa-solid fa-bullhorn"></i>
            </div>
        </div>

        <!-- EN ATENCIÓN -->
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="metric-card gradient-purple dashboard-card">
                <small>EN ATENCIÓN</small>
                <h2><?= number_format($en_atencion) ?></h2>
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

        <!-- FINALIZADOS -->
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="metric-card gradient-green dashboard-card">
                <small>FINALIZADOS</small>
                <h2><?= number_format($atendidos) ?></h2>
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>

        <!-- CANCELADOS -->
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="metric-card gradient-red dashboard-card">
                <small>CANCELADOS</small>
                <h2><?= number_format($cancelados) ?></h2>
                <i class="fa-solid fa-times-circle"></i>
            </div>
        </div>

    </div>

    <!-- Tablas -->
    <div class="row g-4">

        <!-- Top Procesos -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-layer-group text-primary me-2"></i>
                        Top Procesos <span style="opacity: 0.5;">(Más usados)</span>
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Proceso</th>
                                    <th>Código</th>
                                    <th class="text-center">Atenciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($topServicios && $topServicios->num_rows > 0): ?>
                                    <?php while ($row = $topServicios->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nombre_serv']) ?></td>
                                            <td>
                                                <span class="badge badge-custom">
                                                    <?= htmlspecialchars($row['codigo_serv']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong><?= number_format($row['total_atenciones']) ?></strong>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            No existen registros para hoy.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Empleados -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-users text-success me-2"></i>
                        Top Empleados <span style="opacity: 0.5;">(Con más atenciones)</span>
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Empleado</th>
                                    <th>DNI</th>
                                    <th class="text-center">Atenciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($topEmpleados && $topEmpleados->num_rows > 0): ?>
                                    <?php while ($row = $topEmpleados->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nombre_empleado']) ?></td>
                                            <td>
                                                <span class="badge badge-custom">
                                                    <?= htmlspecialchars($row['dni_user'] ?? 'S/C') ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong><?= number_format($row['total_atenciones']) ?></strong>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            No existen atenciones finalizadas el día de hoy.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php include "vista/footer.php"; ?>