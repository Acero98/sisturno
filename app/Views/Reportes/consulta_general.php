<?php
$total = (int) ($metricas['total'] ?? 0);
$atendidos = (int) ($metricas['atendidos'] ?? 0);
$pendientes = (int) ($metricas['pendientes'] ?? 0);
$promedioEspera = (int) round($metricas['promedio_espera'] ?? 0);
$cssModulo = 'consultaGeneral';
include __DIR__ . '/../Layouts/header.php';
?>
<div class="container-fluid py-4 consultation-page">
    <section class="card consultation-filter-card border-0 mb-4">
        <div class="card-body p-4">
            <div class="consultation-toolbar">
                <div class="consultation-title">
                    <span class="consultation-title-icon"><i class="fa-solid fa-magnifying-glass-chart"></i></span>
                    <div><h5 class="mb-0">Consulta General</h5><small>Búsqueda y seguimiento detallado de tickets</small></div>
                </div>
                <div class="consultation-actions">
                    <a id="btnExcel" class="btn-export export-excel" title="Exportar Excel"><i class="fa-solid fa-file-excel"></i><span>Excel</span></a>
                    <a id="btnPDF" class="btn-export export-pdf" title="Exportar PDF"><i class="fa-solid fa-file-pdf"></i><span>PDF</span></a>
                    <button type="button" onclick="window.print()" class="btn-export export-print" title="Imprimir"><i class="fa-solid fa-print"></i><span>Imprimir</span></button>
                </div>
            </div>

            <form method="GET" action="<?= BASE_URL ?>index.php" class="date-filter mt-4">
                <input type="hidden" name="ruta" value="consulta-general">
                <div class="date-field"><label for="consultaInicio">Desde</label><div class="input-icon-wrapper"><i class="fa-solid fa-calendar-days"></i><input id="consultaInicio" type="date" name="inicio" class="form-control" value="<?= htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8') ?>"></div></div>
                <div class="date-field"><label for="consultaFin">Hasta</label><div class="input-icon-wrapper"><i class="fa-solid fa-calendar-check"></i><input id="consultaFin" type="date" name="fin" class="form-control" value="<?= htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8') ?>"></div></div>
                <div class="date-buttons"><button type="submit" name="tipo" value="personalizado" class="btn btn-filter"><i class="fa-solid fa-filter me-2"></i>Filtrar</button><a href="<?= BASE_URL ?>index.php?ruta=consulta-general&amp;tipo=hoy" class="btn btn-reset" title="Restablecer filtros"><i class="fa-solid fa-rotate-left"></i></a></div>
                <div class="active-range"><i class="fa-solid fa-circle-info"></i><span><strong><?= htmlspecialchars(ucfirst($tipo), ENT_QUOTES, 'UTF-8') ?></strong><?= date('d/m/Y', strtotime($fechaInicio)) ?> — <?= date('d/m/Y', strtotime($fechaFin)) ?></span></div>
            </form>
            <input type="hidden" id="tipoFiltro" value="<?= htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </section>

    <section class="metrics-grid mb-4" aria-label="Resumen de consulta">
        <article class="metric-card metric-total"><span class="metric-icon"><i class="fa-solid fa-ticket"></i></span><span><small>Total</small><strong><?= number_format($total) ?></strong></span></article>
        <article class="metric-card metric-finished"><span class="metric-icon"><i class="fa-solid fa-circle-check"></i></span><span><small>Finalizados</small><strong><?= number_format($atendidos) ?></strong></span></article>
        <article class="metric-card metric-pending"><span class="metric-icon"><i class="fa-solid fa-clock"></i></span><span><small>Pendientes</small><strong><?= number_format($pendientes) ?></strong></span></article>
        <article class="metric-card metric-wait"><span class="metric-icon"><i class="fa-solid fa-hourglass-half"></i></span><span><small>Espera promedio</small><strong><?= number_format($promedioEspera) ?> min</strong></span></article>
    </section>

    <section class="card consultation-results-card border-0">
        <div class="card-body p-4">
            <div class="results-title mb-4"><span class="results-title-icon"><i class="fa-solid fa-table-list"></i></span><div><h5 class="mb-0">Resultados de Consulta</h5><small>Filtra o busca tickets dentro del rango seleccionado</small></div></div>

            <div class="secondary-filters mb-4">
                <div class="filter-field"><label for="filtroEstado">Estado</label><div class="select-icon-wrapper"><i class="fa-solid fa-circle-half-stroke"></i><select id="filtroEstado" class="form-select"><option value="">Todos los estados</option><option value="PENDIENTE">PENDIENTE</option><option value="LLAMADO">LLAMADO</option><option value="EN_ATENCION">EN ATENCIÓN</option><option value="FINALIZADO">FINALIZADO</option><option value="CANCELADO">CANCELADO</option></select></div></div>
                <div class="filter-field"><label for="filtroServicio">Servicio</label><div class="select-icon-wrapper"><i class="fa-solid fa-concierge-bell"></i><select id="filtroServicio" class="form-select"><option value="">Todos los servicios</option><?php foreach ($servicios as $servicio): ?><option value="<?= (int) $servicio['id_servicios'] ?>"><?= htmlspecialchars($servicio['nombre_serv'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></div></div>
                <div class="filter-field"><label for="filtroOperador">Operador</label><div class="select-icon-wrapper"><i class="fa-solid fa-headset"></i><select id="filtroOperador" class="form-select"><option value="">Todos los operadores</option><?php foreach ($operadores as $operador): ?><option value="<?= (int) $operador['id_usuario'] ?>"><?= htmlspecialchars($operador['nombre_user'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></div></div>
            </div>

            <div class="table-responsive consultation-table-wrapper">
                <table id="tablaTickets" class="table table-hover table-modern align-middle w-100">
                    <thead><tr><th>#</th><th>Ticket</th><th>Servicio</th><th>Estado</th><th>Operador</th><th>Fecha</th><th>Creado</th><th>Llamado</th><th>En atención</th><th>Finalizado</th></tr></thead>
                </table>
            </div>
        </div>
    </section>
</div>
<script src="<?= BASE_URL ?>assets/plugins/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>assets/plugins/js/jquery.dataTables.min.js"></script>
<script src="<?= BASE_URL ?>assets/plugins/js/dataTables.bootstrap5.min.js"></script>
<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script src="<?= BASE_URL ?>public/js/consulta-general-mvc.js"></script>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>
