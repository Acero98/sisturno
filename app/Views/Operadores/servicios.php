<?php
$cssModulo = 'operador-servicios';
include __DIR__ . '/../../../vista/header.php';
?>

<div class="container-fluid py-4">
    <div class="page-header-card services-header mb-4">
        <div class="row align-items-center"><div class="col-lg-9"><div class="d-flex align-items-center gap-3"><div class="page-header-icon"><i class="fa-solid fa-list-check"></i></div><div><h4 class="mb-1">Asignación de Servicios</h4><p class="mb-0">Configura los servicios disponibles para este operador.</p></div></div></div><div class="col-lg-3 text-end d-none d-lg-block"><i class="fa-solid fa-user-gear page-header-decoration"></i></div></div>
    </div>
    <div class="card content-card border-0"><div class="card-body p-4">
        <div class="operator-summary mb-4"><div class="d-flex align-items-center gap-3"><div class="summary-avatar"><i class="fa-solid fa-user-headset"></i></div><div><h5 class="mb-1"><?= htmlspecialchars($operador['nombre_user']) ?></h5><div class="text-muted small"><span><?= htmlspecialchars($operador['usuario_user']) ?></span><span class="mx-2">•</span><span><?= htmlspecialchars($operador['puesto_user'] ?: 'Sin puesto') ?></span><span class="mx-2">•</span><span>Ventanilla <?= htmlspecialchars($operador['num_ventanilla'] ?: '—') ?></span></div></div></div><a class="btn btn-back" href="<?= BASE_URL ?>index.php?ruta=operadores"><i class="fa-solid fa-arrow-left me-2"></i>Volver</a></div>

        <div class="services-toolbar mb-4"><div><h5 class="mb-1">Servicios activos</h5><small class="text-muted">Selecciona los servicios que este operador puede atender.</small></div><div class="d-flex gap-2"><button type="button" class="btn btn-outline-teal" id="btnSeleccionarTodo"><i class="fa-solid fa-check-double me-2"></i>Todos</button><button type="button" class="btn btn-outline-secondary" id="btnDeseleccionarTodo"><i class="fa-solid fa-xmark me-2"></i>Ninguno</button></div></div>

        <form method="POST" action="<?= BASE_URL ?>index.php?ruta=operadores-servicios&action=guardar" class="formGuardarAsignacionesMvc"><input type="hidden" name="id_usuario" value="<?= $operador['id_usuario'] ?>">
            <?php if ($servicios): ?><div class="row g-3"><?php foreach ($servicios as $servicio): ?><?php $checked = in_array((int) $servicio['id_servicios'], $asignados, true); ?><div class="col-md-6 col-lg-4"><label class="service-selector h-100"><input type="checkbox" name="servicios[]" value="<?= $servicio['id_servicios'] ?>" class="service-checkbox" <?= $checked ? 'checked' : '' ?>><span class="service-card h-100 <?= $checked ? 'is-selected' : '' ?>"><span class="service-icon"><i class="fa-solid fa-concierge-bell"></i></span><span class="service-content"><strong><?= htmlspecialchars($servicio['nombre_serv']) ?></strong><small><?= htmlspecialchars($servicio['codigo_serv']) ?></small></span><span class="service-check"><i class="fa-solid fa-check"></i></span></span></label></div><?php endforeach; ?></div><?php else: ?><div class="empty-services"><i class="fa-solid fa-triangle-exclamation"></i>No existen servicios activos para asignar.</div><?php endif; ?>
            <div class="services-footer mt-4 pt-4"><span class="text-muted small" id="contadorServicios"></span><button type="submit" class="btn btn-save-services"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar asignaciones</button></div>
        </form>
    </div></div>
</div>
<script src="<?= BASE_URL ?>public/js/operador-servicios.js"></script>
<?php include __DIR__ . '/../../../vista/footer.php'; ?>
