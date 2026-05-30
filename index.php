<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/modelo/conexion.php";
require_once __DIR__ . "/control/auth.php";
require_once __DIR__ . "/control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

include_once __DIR__ . "/controlador/dashboard.php";

include "vista/header.php";
?>

<div class="container-fluid py-4">

    <!-- Bienvenida -->
    <div class="welcome-card mb-3 py-2">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold mb-1">
                    <i class="fa-solid fa-gauge-high me-2"></i>
                    Dashboard del Sistema de Turnos
                </h2>
                <p class="mb-0">
                    Bienvenido, <strong><?= strtoupper($_SESSION['usuario']) ?></strong>
                </p>
                <small>
                    <i class="fa-solid fa-calendar-days me-1"></i> <?= $fechaActual ?>
                    &nbsp;&nbsp;
                    <i class="fa-solid fa-clock me-1"></i> <?= $horaActual ?>
                </small>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="fa-solid fa-chart-line" style="font-size: 3.5rem; opacity: 0.12;"></i>
            </div>
        </div>
    </div>

    <!-- Métricas -->
    <div class="row g-4 mb-4">

        <!-- TOTAL DE TICKETS -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-blue dashboard-card">
                <small>TOTAL DE TICKETS</small>
                <h2><?= number_format($total) ?></h2>
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

        <!-- PENDIENTES -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-orange dashboard-card">
                <small>PENDIENTES</small>
                <h2><?= number_format($pendientes) ?></h2>
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <!-- LLAMADOS -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-info dashboard-card">
                <small>LLAMADOS</small>
                <h2><?= number_format($llamados) ?></h2>
                <i class="fa-solid fa-bullhorn"></i>
            </div>
        </div>

        <!-- EN ATENCIÓN -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-purple dashboard-card">
                <small>EN ATENCIÓN</small>
                <h2><?= number_format($en_atencion) ?></h2>
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

        <!-- FINALIZADOS -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-green dashboard-card">
                <small>FINALIZADOS</small>
                <h2><?= number_format($atendidos) ?></h2>
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>

        <!-- CANCELADOS -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-red dashboard-card">
                <small>CANCELADOS</small>
                <h2><?= number_format($cancelados) ?></h2>
                <i class="fa-solid fa-times-circle"></i>
            </div>
        </div>

        <!-- TIEMPO PROMEDIO ESPERA -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-celeste dashboard-card">
                <small>TIEMPO ESPERA</small>
                <h2><?= number_format($promedioEspera) ?> min</h2>
                <i class="fa-solid fa-stopwatch"></i>
            </div>
        </div>

        <!-- TIEMPO PROMEDIO ATENCIÓN -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="metric-card gradient-celeste dashboard-card">
                <small>TIEMPO ATENCIÓN</small>
                <h2><?= number_format($promedioAtencion) ?> min</h2>
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

    </div>

    <!-- Tablas -->
    <div class="row g-4">

        <!-- GRÁFICOS -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-chart-column text-primary me-2"></i>
                        Horas Pico
                    </h5>
                    <div id="chartHorasPico"></div>
                </div>
            </div>
        </div>

        <!-- SERVICIOS MÁS SOLICITADOS -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-chart-bar text-success me-2"></i>
                        Servicios Más Solicitados
                    </h5>
                    <div id="chartServicios"></div>
                </div>
            </div>
        </div>

        <!-- RANKING OPERADORES -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-trophy text-warning me-2"></i>
                        Top Empleados <span style="opacity: 0.5;">(Con más atenciones)</span>
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operador</th>
                                    <th class="text-center">Tickets</th>
                                    <th class="text-center">
                                        Tiempo Promedio
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($rankingOperadores && $rankingOperadores->num_rows > 0): ?>
                                    <?php $posicion = 1; ?>
                                    <?php while ($row = $rankingOperadores->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <strong>
                                                    #<?= $posicion++ ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($row['nombre_user']) ?>
                                            </td>
                                            <td class="text-center">
                                                <strong>
                                                    <?= number_format($row['total_atenciones']) ?>
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">
                                                    <?= round($row['promedio_atencion']) ?> min
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            No existen operadores con atenciones finalizadas hoy.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TENDENCIA SEMANAL -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-chart-line text-success me-2"></i>
                        Tendencia de Tickets (Últimos 7 días)
                    </h5>
                    <div id="chartTendencia"></div>
                </div>
            </div>
        </div>

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

<script src="<?= BASE_URL ?>assets/plugins/js/apexcharts.min.js"></script>
<script src="<?= BASE_URL ?>public/js/dashboard.js"></script>

<script>
    window.dashboardData = {

        horasLabels: <?= json_encode($horasLabels) ?>,
        horasData: <?= json_encode($horasData) ?>,

        serviciosLabels: <?= json_encode($serviciosLabels) ?>,
        serviciosData: <?= json_encode($serviciosData) ?>,

        tendenciaLabels: <?= json_encode($tendenciaLabels) ?>,
        tendenciaData: <?= json_encode($tendenciaData) ?>

    };
</script>

<?php include "vista/footer.php"; ?>