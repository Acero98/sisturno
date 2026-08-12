<?php
$cssModulo = 'servicios';
include __DIR__ . '/../Layouts/header.php';
?>
<div class="container-fluid py-4">
    <div class="services-summary mb-4" aria-label="Resumen de servicios">
        <div class="summary-card summary-total"><span class="summary-icon"><i class="fa-solid fa-layer-group"></i></span><span><small>Total</small><strong><?= (int) ($resumen['total'] ?? 0) ?></strong></span></div>
        <div class="summary-card summary-active"><span class="summary-icon"><i class="fa-solid fa-circle-check"></i></span><span><small>Activos</small><strong><?= (int) ($resumen['activos'] ?? 0) ?></strong></span></div>
        <div class="summary-card summary-inactive"><span class="summary-icon"><i class="fa-solid fa-circle-pause"></i></span><span><small>Inactivos</small><strong><?= (int) ($resumen['inactivos'] ?? 0) ?></strong></span></div>
        <div class="summary-card summary-normal"><span class="summary-icon"><i class="fa-solid fa-minus"></i></span><span><small>Normal</small><strong><?= (int) ($resumen['normal'] ?? 0) ?></strong></span></div>
        <div class="summary-card summary-high"><span class="summary-icon"><i class="fa-solid fa-arrow-up"></i></span><span><small>Alta</small><strong><?= (int) ($resumen['alta'] ?? 0) ?></strong></span></div>
        <div class="summary-card summary-emergency"><span class="summary-icon"><i class="fa-solid fa-triangle-exclamation"></i></span><span><small>Emergencia</small><strong><?= (int) ($resumen['emergencia'] ?? 0) ?></strong></span></div>
    </div>

    <div class="card content-card border-0">
        <div class="card-body p-4">
            <div class="services-toolbar mb-4">
                <div>
                    <button type="button" class="btn btn-new-service" data-bs-toggle="modal" data-bs-target="#modalRegistroServicio">
                        <i class="fa-solid fa-plus me-2"></i>Nuevo Servicio
                    </button>
                </div>
                <div class="services-title">
                    <div class="services-title-icon"><i class="fa-solid fa-concierge-bell"></i></div>
                    <div><h5 class="mb-0">Lista de Servicios</h5><small>Servicios disponibles en el sistema</small></div>
                </div>
                <form id="formBuscarServicios" class="services-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="buscarServicios" type="search" class="form-control" placeholder="Buscar servicio...">
                        <button class="btn btn-search" type="submit">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="tablaServiciosMvc" class="table table-hover table-modern align-middle">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Código</th><th>Prioridad</th><th>Estado</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                    <?php if (!empty($servicios)): ?>
                        <?php foreach ($servicios as $indice => $servicio): ?>
                            <?php $prioridadClase = strtolower($servicio['prioridad_serv']); ?>
                            <tr>
                                <td><span class="service-id"><?= $inicio + $indice + 1 ?></span></td>
                                <td><div class="table-service"><span class="table-service-icon"><i class="fa-solid fa-concierge-bell"></i></span><span class="table-service-name"><?= htmlspecialchars($servicio['nombre_serv']) ?></span></div></td>
                                <td><span class="service-code"><?= htmlspecialchars($servicio['codigo_serv']) ?></span></td>
                                <td><span class="priority-badge priority-<?= htmlspecialchars($prioridadClase) ?>"><i class="fa-solid <?= $prioridadClase === 'emergencia' ? 'fa-triangle-exclamation' : ($prioridadClase === 'alta' ? 'fa-arrow-up' : 'fa-minus') ?>"></i><?= htmlspecialchars(ucfirst(strtolower($servicio['prioridad_serv']))) ?></span></td>
                                <td>
                                    <?php if ((int) $servicio['estado_serv'] === 1): ?>
                                        <span class="status-badge status-active"><span class="status-dot"></span>Activo</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive"><span class="status-dot"></span>Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td><div class="d-flex gap-2 justify-content-center action-buttons">
                                    <button type="button" class="btn btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEditarServicio<?= (int) $servicio['id_servicios'] ?>" title="Editar Servicio"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <form method="POST" action="<?= BASE_URL ?>index.php?ruta=servicios&action=estado" class="formCambiarEstadoServicio d-inline">
                                        <input type="hidden" name="id" value="<?= (int) $servicio['id_servicios'] ?>">
                                        <input type="hidden" name="estado" value="<?= (int) $servicio['estado_serv'] === 1 ? 0 : 1 ?>">
                                        <button class="btn btn-action <?= (int) $servicio['estado_serv'] === 1 ? 'btn-disable' : 'btn-enable' ?>" type="submit" title="<?= (int) $servicio['estado_serv'] === 1 ? 'Desactivar' : 'Activar' ?> Servicio"><i class="fa-solid <?= (int) $servicio['estado_serv'] === 1 ? 'fa-circle-xmark' : 'fa-circle-check' ?>"></i></button>
                                    </form>
                                </div></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5"><div class="empty-state"><i class="fa-solid fa-layer-group fa-2x mb-3"></i><div>No se encontraron servicios.</div></div></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
unset($servicio);
require __DIR__ . '/modal_crear.php';
foreach ($servicios as $servicio) {
    require __DIR__ . '/modal_editar.php';
}
?>
<script src="<?= BASE_URL ?>public/js/servicios-mvc.js"></script>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>
