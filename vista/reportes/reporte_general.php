<?php

//require_once __DIR__ . "/../../modelo/conexion.php";
require_once __DIR__ . "/../../modelo/conexion.php";

require_once __DIR__ . "/../../control/auth.php";
require_once __DIR__ . "/../../control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

include_once __DIR__ . "/../../controlador/reportes/reporteGeneral.php";

include "../header.php";

?>

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="page-header-card mb-2 py-2">
        <div class="row align-items-center">

            <div class="col-lg-9">
                <h5 class="mb-1">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    Reporte General de Tickets
                </h5>

                <p class="mb-0">
                    Visualización de métricas, estados y rendimiento del sistema en tiempo real.
                </p>
            </div>

            <div class="col-lg-3 text-end d-none d-lg-block">
                <i class="fa-solid fa-chart-pie"
                    style="font-size: 2.6rem; opacity: 0.10;"></i>
            </div>

        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 dashboard-card">
        <div class="card-body p-3">

            <!-- HEADER + FILTROS RÁPIDOS -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

                <h6 class="fw-bold mb-0">
                    <i class="fa-solid fa-chart-line text-primary me-1"></i>
                    Reporte
                </h6>

                <div class="d-flex gap-1 flex-wrap">
                    <a href="?tipo=hoy"
                        class="btn btn-sm <?= $tipo == 'hoy' ? 'btn-primary' : 'btn-outline-primary' ?>">Hoy</a>

                    <a href="?tipo=semana"
                        class="btn btn-sm <?= $tipo == 'semana' ? 'btn-primary' : 'btn-outline-primary' ?>">Semana</a>

                    <a href="?tipo=mes"
                        class="btn btn-sm <?= $tipo == 'mes' ? 'btn-primary' : 'btn-outline-primary' ?>">Mes</a>

                    <a href="?tipo=anio"
                        class="btn btn-sm <?= $tipo == 'anio' ? 'btn-primary' : 'btn-outline-primary' ?>">Año</a>
                </div>

            </div>

            <!-- FORM + EXPORTACIÓN EN UNA FILA -->
            <form method="GET">
                <div class="row g-2 align-items-center">

                    <div class="col-md-3">
                        <input type="date" name="inicio"
                            class="form-control form-control-sm"
                            value="<?= htmlspecialchars($fechaInicio) ?>">
                    </div>

                    <div class="col-md-3">
                        <input type="date" name="fin"
                            class="form-control form-control-sm"
                            value="<?= htmlspecialchars($fechaFin) ?>">
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" name="tipo" value="personalizado"
                            class="btn btn-sm btn-success w-100">
                            Filtrar
                        </button>

                        <a href="?tipo=hoy"
                            class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>

                    <!-- EXPORTACIÓN COMPACTA -->
                    <div class="col-md-4 d-flex justify-content-end gap-1 flex-wrap">

                        <a href="../../controlador/reportes/exp_estadosExcel.php?tipo=<?= $tipo ?>&inicio=<?= $fechaInicio ?>&fin=<?= $fechaFin ?>"
                            class="btn btn-sm btn-success">
                            <i class="fa-solid fa-file-excel"></i>
                        </a>

                        <!-- <a href="exportar_pdf.php?tipo=<= $tipo ?>&inicio=<= $fechaInicio ?>&fin=<= $fechaFin ?>"
                            class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>

                        <button onclick="window.print()" class="btn btn-sm btn-dark">
                            <i class="fa-solid fa-print"></i>
                        </button> -->

                    </div>

                </div>
            </form>

            <!-- INFO ULTRA RESUMIDA -->
            <div class="mt-2 small text-muted">
                <i class="fa-solid fa-circle-info me-1"></i>
                <?= ucfirst($tipo) ?>:
                <?= date('d/m/Y', strtotime($fechaInicio)) ?> -
                <?= date('d/m/Y', strtotime($fechaFin)) ?>
            </div>

        </div>
    </div>

    <!--
    <div class="card shadow-sm border-0 mb-4 dashboard-card">

        <div class="card-body p-4">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-chart-line text-primary me-2"></i>
                        Filtros del Reporte General
                    </h5>

                    <p class="text-muted mb-0">
                        Analiza métricas, tendencias y rendimiento operativo del sistema mediante filtros por rango de fechas y herramientas de exportación.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">

                    <a href="?tipo=hoy"
                        class="btn <= $tipo == 'hoy' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fa-solid fa-calendar-day me-1"></i>
                        Hoy
                    </a>

                    <a href="?tipo=semana"
                        class="btn <= $tipo == 'semana' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fa-solid fa-calendar-week me-1"></i>
                        Semana
                    </a>

                    <a href="?tipo=mes"
                        class="btn <= $tipo == 'mes' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fa-solid fa-calendar-alt me-1"></i>
                        Mes
                    </a>

                    <a href="?tipo=anio"
                        class="btn <= $tipo == 'anio' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fa-solid fa-calendar me-1"></i>
                        Año
                    </a>

                </div>

            </div>

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">
                            Fecha Inicial
                        </label>

                        <input
                            type="date"
                            name="inicio"
                            class="form-control"
                            value="<= htmlspecialchars($fechaInicio) ?>">

                    </div>

                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">
                            Fecha Final
                        </label>

                        <input
                            type="date"
                            name="fin"
                            class="form-control"
                            value="<= htmlspecialchars($fechaFin) ?>">

                    </div>

                    <div class="col-lg-4">

                        <div class="d-grid gap-2 d-md-flex">

                            <button
                                type="submit"
                                name="tipo"
                                value="personalizado"
                                class="btn btn-success flex-fill">

                                <i class="fa-solid fa-filter me-1"></i>
                                Aplicar Filtro
                            </button>

                            <a
                                href="?tipo=hoy"
                                class="btn btn-outline-secondary flex-fill">

                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Limpiar
                            </a>

                        </div>

                    </div>

                </div>

            </form>

            <div class="row mt-4">

                <div class="col-lg-6">

                    <div class="alert alert-light border h-100 mb-0">

                        <div class="d-flex align-items-center mb-2">

                            <i class="fa-solid fa-circle-info text-primary me-2"></i>

                            <strong>
                                Información Actual
                            </strong>

                        </div>

                        <div class="small">

                            <div class="mb-1">
                                <strong>Tipo:</strong>
                                <= ucfirst($tipo) ?>
                            </div>

                            <div>
                                <strong>Rango:</strong>
                                <= date('d/m/Y', strtotime($fechaInicio)) ?>
                                —
                                <= date('d/m/Y', strtotime($fechaFin)) ?>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 mt-3 mt-lg-0">

                    <div class="card border-0 bg-light h-100">

                        <div class="card-body">

                            <div class="d-flex align-items-center mb-3">

                                <i class="fa-solid fa-download text-success me-2"></i>

                                <strong>
                                    Exportaciones
                                </strong>

                            </div>

                            <div class="d-flex flex-wrap gap-2">

                                <a
                                    href="../../controlador/reportes/generar_excel.php?tipo=<= $tipo ?>&inicio=<= $fechaInicio ?>&fin=<= $fechaFin ?>"
                                    class="btn btn-success">

                                    <i class="fa-solid fa-file-excel me-1"></i>
                                    Excel
                                </a>

                                <a
                                    href="exportar_pdf.php?tipo=<= $tipo ?>&inicio=<= $fechaInicio ?>&fin=<= $fechaFin ?>"
                                    class="btn btn-danger">

                                    <i class="fa-solid fa-file-pdf me-1"></i>
                                    PDF
                                </a>

                                <button
                                    onclick="window.print()"
                                    class="btn btn-dark">

                                    <i class="fa-solid fa-print me-1"></i>
                                    Imprimir
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>-->

    <!-- Métricas -->
    <div class="row g-2 mb-3">

        <!-- CARD -->
        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-blue dashboard-card text-center p-2">
                <small>TOTAL</small>
                <h5 class="mb-0"><?= number_format($total) ?></h5>
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-orange dashboard-card text-center p-2">
                <small>PENDIENTES</small>
                <h5 class="mb-0"><?= number_format($pendientes) ?></h5>
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-info dashboard-card text-center p-2">
                <small>LLAMADOS</small>
                <h5 class="mb-0"><?= number_format($llamados) ?></h5>
                <i class="fa-solid fa-bullhorn"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-purple dashboard-card text-center p-2">
                <small>EN ATENCIÓN</small>
                <h5 class="mb-0"><?= number_format($en_atencion) ?></h5>
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-green dashboard-card text-center p-2">
                <small>FINALIZADOS</small>
                <h5 class="mb-0"><?= number_format($atendidos) ?></h5>
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-red dashboard-card text-center p-2">
                <small>CANCELADOS</small>
                <h5 class="mb-0"><?= number_format($cancelados) ?></h5>
                <i class="fa-solid fa-times-circle"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-orange dashboard-card text-center p-2">
                <small>ESPERA</small>
                <h5 class="mb-0"><?= number_format($promedioEspera) ?> min</h5>
                <i class="fa-solid fa-stopwatch"></i>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6">
            <div class="metric-card gradient-green dashboard-card text-center p-2">
                <small>ATENCIÓN</small>
                <h5 class="mb-0"><?= number_format($promedioAtencion) ?> min</h5>
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

    </div>

    <!-- Tablas -->
    <div class="row g-4">

        <!-- GRÁFICOS -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-chart-column text-primary me-2"></i>
                            Horas Pico
                        </h5>
                        <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>controlador/reportes/exp_horaspicoExcel.php?tipo=<?= $tipo ?>&inicio=<?= $fechaInicio ?>&fin=<?= $fechaFin ?>"
                                class="btn btn-sm btn-success"
                                title="Exportar Excel">
                                <i class="fa-solid fa-file-excel"></i>
                            </a>
                        </div>
                    </div>
                    <!-- CHART -->
                    <div id="chartHorasPico"></div>
                </div>
            </div>
        </div>

        <!-- SERVICIOS MÁS SOLICITADOS -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-chart-bar text-success me-2"></i>
                            Servicios Más Solicitados
                        </h5>
                        <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>controlador/reportes/exp_serviciosExcel.php?tipo=<?= $tipo ?>&inicio=<?= $fechaInicio ?>&fin=<?= $fechaFin ?>"
                                class="btn btn-sm btn-success"
                                title="Exportar Excel">
                                <i class="fa-solid fa-file-excel"></i>
                            </a>
                        </div>
                    </div>
                    <div id="chartServicios"></div>
                </div>
            </div>
        </div>

        <!-- RANKING OPERADORES -->
        <div class="col-lg-6">
            <div class="card dashboard-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>
                            Top Empleados
                        </h5>
                        <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>controlador/reportes/exp_topoperadoresExcel.php?tipo=<?= $tipo ?>&inicio=<?= $fechaInicio ?>&fin=<?= $fechaFin ?>"
                                class="btn btn-sm btn-success">
                                <i class="fa-solid fa-file-excel"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operador</th>

                                    <th class="text-center">
                                        Tickets
                                    </th>

                                    <th class="text-center">
                                        Finalizados
                                    </th>

                                    <th class="text-center">
                                        Cancelados
                                    </th>

                                    <th class="text-center">
                                        Promedio
                                    </th>

                                    <th class="text-center">
                                        Eficiencia
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if ($rankingOperadores && $rankingOperadores->num_rows > 0): ?>

                                    <?php $posicion = 1; ?>

                                    <?php while ($row = $rankingOperadores->fetch_assoc()): ?>

                                        <?php

                                        $eficiencia = 0;

                                        if ($row['total_atenciones'] > 0) {

                                            $eficiencia = (
                                                $row['finalizados']
                                                /
                                                $row['total_atenciones']
                                            ) * 100;
                                        }

                                        ?>

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
                                                <span class="badge bg-dark">
                                                    <?= number_format($row['total_atenciones']) ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    <?= number_format($row['finalizados']) ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-danger">
                                                    <?= number_format($row['cancelados']) ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-primary">
                                                    <?= round($row['promedio_atencion']) ?> min
                                                </span>
                                            </td>

                                            <td class="text-center">

                                                <?php if ($eficiencia >= 80): ?>

                                                    <span class="badge bg-success">
                                                        <?= round($eficiencia, 1) ?>%
                                                    </span>

                                                <?php elseif ($eficiencia >= 50): ?>

                                                    <span class="badge bg-warning text-dark">
                                                        <?= round($eficiencia, 1) ?>%
                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge bg-danger">
                                                        <?= round($eficiencia, 1) ?>%
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No existen operadores con atenciones finalizadas.
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-chart-line text-success me-2"></i>
                            Tendencia de Tickets
                        </h5>
                        <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>controlador/reportes/exp_tendenciaExcel.php?tipo=<?= $tipo ?>&inicio=<?= $fechaInicio ?>&fin=<?= $fechaFin ?>"
                                class="btn btn-sm btn-success">
                                <i class="fa-solid fa-file-excel"></i>
                            </a>
                        </div>
                    </div>
                    <div id="chartTendencia"></div>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="<?= BASE_URL ?>assets/plugins/js/apexcharts.min.js"></script>
<script src="<?= BASE_URL ?>public/js/reporteGeneral.js"></script>

<script>
    window.dashboardData = {

        horasLabels: <?= json_encode($horasLabels) ?>,
        horasData: <?= json_encode($horasData) ?>,

        serviciosLabels: <?= json_encode($serviciosLabels) ?>,
        serviciosData: <?= json_encode($serviciosData) ?>,

        tendenciaLabels: <?= json_encode($tendenciaLabels) ?>,
        tendenciaData: <?= json_encode($tendenciaData) ?>,

        estadosLabels: <?= json_encode($estadoLabels) ?>,
        estadosData: <?= json_encode($estadoData) ?>

    };
</script>

<?php

include "../footer.php";

?>