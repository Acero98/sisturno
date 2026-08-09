<?php
$total = (int) ($metricas['total'] ?? 0);
$atendidos = (int) ($metricas['atendidos'] ?? 0);
$pendientes = (int) ($metricas['pendientes'] ?? 0);
$promedioEspera = (int) round($metricas['promedio_espera'] ?? 0);
require __DIR__ . '/../../../vista/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>public/css/consultaGeneral.css">

<div class="container-fluid py-4">
    <div class="page-header-card mb-2 py-2">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h5 class="mb-1"><i class="fa-solid fa-magnifying-glass-chart me-2"></i>Consulta General de Tickets</h5>
                <p class="mb-0">Búsqueda y filtrado de tickets por fecha, estado, servicio y operador.</p>
            </div>
            <div class="col-lg-3 text-end d-none d-lg-block"><i class="fa-solid fa-table-list" style="font-size: 2.6rem; opacity: .1;"></i></div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 dashboard-card">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-filter text-primary me-1"></i>Filtros</h6>
            </div>
            <form method="GET" action="<?= BASE_URL ?>index.php">
                <input type="hidden" name="ruta" value="consulta-general">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3"><input type="date" name="inicio" class="form-control form-control-sm" value="<?= htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div class="col-md-3"><input type="date" name="fin" class="form-control form-control-sm" value="<?= htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div class="col-md-2 d-flex gap-1"><button type="submit" name="tipo" value="personalizado" class="btn btn-sm btn-success w-100">Filtrar</button><a href="<?= BASE_URL ?>index.php?ruta=consulta-general&amp;tipo=hoy" class="btn btn-sm btn-outline-secondary" title="Restablecer"><i class="fa-solid fa-rotate-left"></i></a></div>
                    <div class="col-md-3 d-flex gap-1 justify-content-end"><a id="btnExcel" class="btn btn-sm btn-success" title="Exportar Excel"><i class="fa-solid fa-file-excel"></i></a><a id="btnPDF" class="btn btn-sm btn-danger" title="Exportar PDF"><i class="fa-solid fa-file-pdf"></i></a><button type="button" onclick="window.print()" class="btn btn-sm btn-dark" title="Imprimir"><i class="fa-solid fa-print"></i></button></div>
                </div>
            </form>
            <div class="mt-2 small text-muted"><i class="fa-solid fa-circle-info me-1"></i><?= htmlspecialchars(ucfirst($tipo), ENT_QUOTES, 'UTF-8') ?>: <?= date('d/m/Y', strtotime($fechaInicio)) ?> - <?= date('d/m/Y', strtotime($fechaFin)) ?></div>
            <input type="hidden" id="tipoFiltro" value="<?= htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2"><small class="text-muted d-block">TOTAL</small>
                <h5 class="fw-bold mb-0"><?= number_format($total) ?></h5>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2"><small class="text-muted d-block">FINALIZADOS</small>
                <h5 class="fw-bold text-success mb-0"><?= number_format($atendidos) ?></h5>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2"><small class="text-muted d-block">PENDIENTES</small>
                <h5 class="fw-bold text-warning mb-0"><?= number_format($pendientes) ?></h5>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2"><small class="text-muted d-block">ESPERA</small>
                <h5 class="fw-bold text-primary mb-0"><?= number_format($promedioEspera) ?>m</h5>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 dashboard-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-table text-primary me-2"></i>Resultados de Consulta</h5><small class="text-muted">Use los filtros o el buscador de la tabla para consultar tickets.</small>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-lg-4"><label class="form-label fw-semibold" for="filtroEstado">Estado</label><select id="filtroEstado" class="form-select">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="LLAMADO">LLAMADO</option>
                        <option value="EN_ATENCION">EN ATENCION</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                        <option value="CANCELADO">CANCELADO</option>
                    </select></div>
                <div class="col-lg-4"><label class="form-label fw-semibold" for="filtroServicio">Servicio</label><select id="filtroServicio" class="form-select">
                        <option value="">Todos</option><?php foreach ($servicios as $servicio): ?><option value="<?= (int) $servicio['id_servicios'] ?>"><?= htmlspecialchars($servicio['nombre_serv'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
                    </select></div>
                <div class="col-lg-4"><label class="form-label fw-semibold" for="filtroOperador">Operador</label><select id="filtroOperador" class="form-select">
                        <option value="">Todos</option><?php foreach ($operadores as $operador): ?><option value="<?= (int) $operador['id_usuario'] ?>"><?= htmlspecialchars($operador['nombre_user'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
                    </select></div>
            </div>
            <div class="table-responsive">
                <table id="tablaTickets" class="table table-hover table-striped align-middle w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Operador</th>
                            <th>Fecha</th>
                            <th>Creado</th>
                            <th>Llamado</th>
                            <th>En atención</th>
                            <th>Finalizado</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/plugins/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>assets/plugins/js/jquery.dataTables.min.js"></script>
<script src="<?= BASE_URL ?>assets/plugins/js/dataTables.bootstrap5.min.js"></script>
<script>
    window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= BASE_URL ?>public/js/consulta-general-mvc.js"></script>
<?php require __DIR__ . '/../../../vista/footer.php'; ?>