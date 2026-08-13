<?php
$cssModulo = 'reporte-general';
include __DIR__ . '/../Layouts/header.php';

$tarjetasMetricas = [
    ['Total', $metricas['total'] ?? 0, 'fa-ticket', 'metric-total'],
    ['Pendientes', $metricas['pendientes'] ?? 0, 'fa-clock', 'metric-pending'],
    ['Llamados', $metricas['llamados'] ?? 0, 'fa-bullhorn', 'metric-called'],
    ['En atención', $metricas['en_atencion'] ?? 0, 'fa-headset', 'metric-attention'],
    ['Finalizados', $metricas['atendidos'] ?? 0, 'fa-circle-check', 'metric-finished'],
    ['Cancelados', $metricas['cancelados'] ?? 0, 'fa-circle-xmark', 'metric-cancelled'],
];
?>
<div class="container-fluid py-4 report-page">
    <div class="card report-filter-card border-0 mb-4">
        <div class="card-body p-4">
            <div class="report-toolbar">
                <div class="report-title">
                    <span class="report-title-icon"><i class="fa-solid fa-chart-line"></i></span>
                    <div>
                        <h5 class="mb-0">Reporte General</h5><small>Métricas y rendimiento del sistema</small>
                    </div>
                </div>
                <div class="period-selector" aria-label="Seleccionar periodo">
                    <?php foreach (['hoy' => 'Hoy', 'semana' => 'Semana', 'mes' => 'Mes', 'anio' => 'Año'] as $clave => $etiqueta): ?>
                        <a href="<?= BASE_URL ?>index.php?ruta=reporte-general&tipo=<?= $clave ?>" class="period-btn <?= $tipo === $clave ? 'is-active' : '' ?>"><?= $etiqueta ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <form method="GET" action="<?= BASE_URL ?>index.php" class="date-filter mt-4">
                <input type="hidden" name="ruta" value="reporte-general">
                <div class="date-field"><label for="reporteInicio">Desde</label>
                    <div class="input-icon-wrapper"><i class="fa-solid fa-calendar-days"></i><input id="reporteInicio" type="date" name="inicio" class="form-control" value="<?= htmlspecialchars($fechaInicio) ?>"></div>
                </div>
                <div class="date-field"><label for="reporteFin">Hasta</label>
                    <div class="input-icon-wrapper"><i class="fa-solid fa-calendar-check"></i><input id="reporteFin" type="date" name="fin" class="form-control" value="<?= htmlspecialchars($fechaFin) ?>"></div>
                </div>
                <button type="submit" name="tipo" value="personalizado" class="btn btn-filter"><i class="fa-solid fa-filter me-2"></i>Filtrar</button>
                <div class="active-range"><i class="fa-solid fa-circle-info"></i><span><strong><?= htmlspecialchars(ucfirst($tipo)) ?></strong><?= date('d/m/Y', strtotime($fechaInicio)) ?> — <?= date('d/m/Y', strtotime($fechaFin)) ?></span></div>
            </form>
        </div>
    </div>

    <div class="export-bar mb-4">
        <div class="export-label"><span class="export-icon"><i class="fa-solid fa-file-export"></i></span><span><strong>Exportar reportes</strong><small>Descarga la información en Excel</small></span></div>
        <div class="export-actions">
            <a class="btn-export" href="<?= BASE_URL ?>app/Models/exp_tiempoServiciosExcel.php?inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>"><i class="fa-solid fa-file-excel"></i>Tiempo por servicio</a>
            <a class="btn-export" href="<?= BASE_URL ?>app/Models/exp_topoperadoresExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>"><i class="fa-solid fa-file-excel"></i>Operadores</a>
            <a class="btn-export" href="<?= BASE_URL ?>app/Models/exp_estadosExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>"><i class="fa-solid fa-file-excel"></i>Estados</a>
            <a class="btn-export" href="<?= BASE_URL ?>app/Models/exp_serviciosExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>"><i class="fa-solid fa-file-excel"></i>Servicios</a>
            <a class="btn-export" href="<?= BASE_URL ?>app/Models/exp_tendenciaExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>"><i class="fa-solid fa-file-excel"></i>Tendencia</a>
            <a class="btn-export" href="<?= BASE_URL ?>app/Models/exp_horaspicoExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>"><i class="fa-solid fa-file-excel"></i>Horas pico</a>
        </div>
    </div>

    <section class="metrics-grid mb-4" aria-label="Resumen de tickets">
        <?php foreach ($tarjetasMetricas as [$titulo, $valor, $icono, $clase]): ?>
            <article class="metric-card <?= $clase ?>">
                <span class="metric-icon"><i class="fa-solid <?= $icono ?>"></i></span>
                <span class="metric-content">
                    <small><?= $titulo ?></small>
                    <strong><?= number_format($valor) ?></strong>
                </span>
            </article>
        <?php endforeach; ?>
    </section>

    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <section class="card report-card border-0 h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3"><span class="section-icon icon-blue"><i class="fa-solid fa-stopwatch"></i></span>
                        <div>
                            <h6>Tiempo promedio por servicio</h6><small>Duración de tickets finalizados</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th class="text-center">Finalizados</th>
                                    <th class="text-center">Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tiempoPorServicio)): ?>
                                    <?php foreach ($tiempoPorServicio as $servicioTiempo): ?><tr>
                                            <td>
                                                <div class="service-cell"><span class="service-avatar"><i class="fa-solid fa-concierge-bell"></i></span><span><strong><?= htmlspecialchars($servicioTiempo['nombre_serv']) ?></strong><small><?= htmlspecialchars($servicioTiempo['codigo_serv']) ?></small></span></div>
                                            </td>
                                            <td class="text-center"><span class="count-chip"><?= number_format($servicioTiempo['finalizados']) ?></span></td>
                                            <td class="text-center"><span class="time-chip"><i class="fa-regular fa-clock"></i><?= round($servicioTiempo['promedio_atencion']) ?> min</span></td>
                                        </tr><?php endforeach; ?>
                                <?php else: ?><tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="empty-state"><i class="fa-solid fa-clock-rotate-left"></i><span>No hay atenciones finalizadas por servicio.</span></div>
                                        </td>
                                    </tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xl-6">
            <section class="card report-card border-0 h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3"><span class="section-icon icon-amber"><i class="fa-solid fa-trophy"></i></span>
                        <div>
                            <h6>Top Empleados</h6><small>Rendimiento de operadores</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern ranking-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Operador</th>
                                    <th class="text-center">Tickets</th>
                                    <th class="text-center">Finalizados</th>
                                    <th class="text-center">Cancelados</th>
                                    <th class="text-center">Promedio</th>
                                    <th class="text-center">Eficiencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rankingOperadores)): ?>
                                    <?php foreach ($rankingOperadores as $posicion => $operador): ?><?php $tickets = (int) $operador['tickets'];
                                                                                                    $finalizados = (int) $operador['finalizados'];
                                                                                                    $eficiencia = $tickets ? round(($finalizados / $tickets) * 100) : 0; ?>
                                    <tr>
                                        <td><span class="rank-position <?= $posicion < 3 ? 'rank-top' : '' ?>"><?= $posicion + 1 ?></span></td>
                                        <td>
                                            <div class="operator-cell"><span class="operator-avatar"><i class="fa-solid fa-user"></i></span><strong><?= htmlspecialchars($operador['nombre_user']) ?></strong></div>
                                        </td>
                                        <td class="text-center"><?= number_format($tickets) ?></td>
                                        <td class="text-center"><?= number_format($finalizados) ?></td>
                                        <td class="text-center"><?= number_format($operador['cancelados']) ?></td>
                                        <td class="text-center"><span class="time-chip"><?= round($operador['promedio_atencion'] ?? 0) ?> min</span></td>
                                        <td class="text-center"><span class="efficiency-chip <?= $eficiencia >= 80 ? 'efficiency-high' : 'efficiency-medium' ?>"><?= $eficiencia ?>%</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?><tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state"><i class="fa-solid fa-users-slash"></i><span>No existen operadores con tickets en este rango.</span></div>
                                    </td>
                                </tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach (
            [
                ['Horas pico', 'Demanda por franja horaria', 'fa-clock', 'icon-amber', 'graficoHorasPico'],
                ['Servicios más solicitados', 'Atenciones por servicio', 'fa-chart-bar', 'icon-blue', 'graficoServicios'],
                ['Estados de tickets', 'Distribución por estado', 'fa-chart-pie', 'icon-violet', 'graficoEstados'],
                ['Tendencia ' . ($agrupacionTendencia ?? 'diaria') . ' de tickets', 'Agrupación automática según el rango', 'fa-chart-line', 'icon-green', 'graficoTendencia']
            ] as
            [$tituloGrafica, $subtituloGrafica, $iconoGrafica, $claseGrafica, $idGrafica]
        ): ?>
            <div class="col-xl-6">
                <section class="card report-card chart-card border-0 h-100">
                    <div class="card-body p-4">
                        <div class="section-title mb-3"><span class="section-icon <?= $claseGrafica ?>"><i class="fa-solid <?= $iconoGrafica ?>"></i></span>
                            <div>
                                <h6><?= $tituloGrafica ?></h6><small><?= $subtituloGrafica ?></small>
                            </div>
                        </div>
                        <div id="<?= $idGrafica ?>" class="report-chart"></div>
                    </div>
                </section>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="<?= BASE_URL ?>assets/plugins/js/apexcharts.min.js"></script>
<script>
    window.reporteGeneralData = <?= json_encode($graficas, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= BASE_URL ?>public/js/reporte-general-mvc.js"></script>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>