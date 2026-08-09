<?php
/*
 * Puente temporal de migración: conserva el marcado y los estilos actuales
 * mientras los componentes del reporte se trasladan gradualmente a app/Views.
 */
?>
<?php include __DIR__ . '/../../../vista/header.php'; ?>
<div class="container-fluid py-4">
    <div class="page-header-card mb-2 py-2">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h5 class="mb-1"><i class="fa-solid fa-chart-line me-2"></i>Reporte General de Tickets</h5>
                <p class="mb-0">Visualización de métricas, estados y rendimiento del sistema.</p>
            </div>
            <div class="col-lg-3 text-end d-none d-lg-block"><i class="fa-solid fa-chart-pie" style="font-size:2.6rem;opacity:.10"></i></div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mb-3 dashboard-card">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-line text-primary me-1"></i>Reporte</h6>
                <div class="d-flex gap-1 flex-wrap"><?php foreach (['hoy' => 'Hoy', 'semana' => 'Semana', 'mes' => 'Mes', 'anio' => 'Año'] as $clave => $etiqueta): ?>
                        <a href="<?= BASE_URL ?>index.php?ruta=reporte-general&tipo=<?= $clave ?>" class="btn btn-sm <?= $tipo === $clave ? 'btn-primary' : 'btn-outline-primary' ?>"><?= $etiqueta ?></a><?php endforeach; ?>
                </div>
            </div>
            <form method="GET" action="<?= BASE_URL ?>index.php"><input type="hidden" name="ruta" value="reporte-general">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3"><input type="date" name="inicio" class="form-control form-control-sm" value="<?= htmlspecialchars($fechaInicio) ?>"></div>
                    <div class="col-md-3"><input type="date" name="fin" class="form-control form-control-sm" value="<?= htmlspecialchars($fechaFin) ?>"></div>
                    <div class="col-md-2"><button type="submit" name="tipo" value="personalizado" class="btn btn-sm btn-success w-100">Filtrar</button></div>
                </div>
            </form>
            <div class="mt-2 small text-muted"><i class="fa-solid fa-circle-info me-1"></i><?= ucfirst($tipo) ?>: <?= date('d/m/Y', strtotime($fechaInicio)) ?> - <?= date('d/m/Y', strtotime($fechaFin)) ?></div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-1 mb-3 flex-wrap">
        <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>controlador/reportes/exp_estadosExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>">
            <i class="fa-solid fa-file-excel me-1"></i>Estados</a>
        <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>controlador/reportes/exp_serviciosExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>">
            <i class="fa-solid fa-file-excel me-1"></i>Servicios</a>
        <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>controlador/reportes/exp_topoperadoresExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>">
            <i class="fa-solid fa-file-excel me-1"></i>Operadores</a>
        <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>controlador/reportes/exp_horaspicoExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>">
            <i class="fa-solid fa-file-excel me-1"></i>Horas pico</a>
        <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>controlador/reportes/exp_tendenciaExcel.php?tipo=<?= urlencode($tipo) ?>&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>">
            <i class="fa-solid fa-file-excel me-1"></i>Tendencia</a>
        <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>controlador/reportes/exp_tiempoServiciosExcel.php?inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>">
            <i class="fa-solid fa-file-excel me-1"></i>Exportar tiempo por servicio</a>
    </div>
    <div class="row g-3 mb-4">
        <?php foreach (
            [
                ['Total', $metricas['total'] ?? 0, 'text-primary'],
                ['Pendientes', $metricas['pendientes'] ?? 0, 'text-warning'],
                ['Llamados', $metricas['llamados'] ?? 0, 'text-info'],
                ['En atención', $metricas['en_atencion'] ?? 0, 'text-primary'],
                ['Finalizados', $metricas['atendidos'] ?? 0, 'text-success'],
                ['Cancelados', $metricas['cancelados'] ?? 0, 'text-danger']
            ]
            as [$titulo, $valor, $clase]
        ): ?>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="<?= $clase ?> mb-1"><?= $titulo ?></h6>
                        <h3 class="mb-0"><?= number_format($valor) ?></h3>
                    </div>
                </div>
            </div><?php endforeach; ?>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 dashboard-card h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-stopwatch text-primary me-2"></i>Tiempo promedio por servicio</h6>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th class="text-center">Finalizados</th>
                                    <th class="text-center">Promedio</th>
                                </tr>
                            </thead>
                            <tbody><?php if ($tiempoPorServicio): ?><?php foreach ($tiempoPorServicio as $servicioTiempo): ?><tr>
                                    <td><strong><?= htmlspecialchars($servicioTiempo['nombre_serv']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($servicioTiempo['codigo_serv']) ?></small></td>
                                    <td class="text-center"><?= number_format($servicioTiempo['finalizados']) ?></td>
                                    <td class="text-center"><span class="badge bg-primary"><?= round($servicioTiempo['promedio_atencion']) ?> min</span></td>
                                </tr><?php endforeach; ?><?php else: ?><tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No hay atenciones finalizadas por servicio.</td>
                                </tr><?php endif; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 dashboard-card h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-trophy text-warning me-2"></i>Top Empleados</h6>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
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
                            <tbody><?php if ($rankingOperadores): ?><?php foreach ($rankingOperadores as $posicion => $operador): ?><?php $tickets = (int) $operador['tickets'];
                                                                                                                                    $finalizados = (int) $operador['finalizados'];
                                                                                                                                    $eficiencia = $tickets ? round(($finalizados / $tickets) * 100) : 0; ?><tr>
                                    <td><strong>#<?= $posicion + 1 ?></strong></td>
                                    <td><?= htmlspecialchars($operador['nombre_user']) ?></td>
                                    <td class="text-center"><strong><?= number_format($tickets) ?></strong></td>
                                    <td class="text-center"><strong><?= number_format($finalizados) ?></strong></td>
                                    <td class="text-center"><strong><?= number_format($operador['cancelados']) ?></strong></td>
                                    <td class="text-center"><span class="badge bg-primary"><?= round($operador['promedio_atencion'] ?? 0) ?> min</span></td>
                                    <td class="text-center"><span class="badge <?= $eficiencia >= 80 ? 'bg-success' : 'bg-warning text-dark' ?>"><?= $eficiencia ?>%</span></td>
                                </tr><?php endforeach; ?><?php else: ?><tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No existen operadores con tickets en este rango.</td>
                                </tr><?php endif; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 dashboard-card h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3">Estados de tickets</h6>
                    <div id="graficoEstados"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 dashboard-card h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3">Servicios más solicitados</h6>
                    <div id="graficoServicios"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 dashboard-card h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3">Tendencia de tickets</h6>
                    <div id="graficoTendencia"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 dashboard-card h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3">Horas pico</h6>
                    <div id="graficoHorasPico"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>assets/plugins/js/apexcharts.min.js"></script>
<script>
    window.reporteGeneralData = <?= json_encode($graficas, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= BASE_URL ?>public/js/reporte-general-mvc.js"></script>
<?php include __DIR__ . '/../../../vista/footer.php'; ?>