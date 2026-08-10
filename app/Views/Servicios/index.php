<?php include __DIR__ . '/../Layouts/header.php'; ?>
<div class="container-fluid py-4">
    <div class="page-header-card mb-3 py-2">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h4 class="mb-1"><i class="fa-solid fa-concierge-bell me-2"></i>Gestión de Servicios</h4>
                <p class="mb-0">Administra los servicios disponibles para la generación y atención de tickets.</p>
            </div>
            <div class="col-lg-3 text-end d-none d-lg-block"><i class="fa-solid fa-layer-group" style="font-size:3.5rem;opacity:.12"></i></div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Servicios</h6>
                    <h3 class="mb-0"><?= $resumen['total'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-success mb-1">Activos</h6>
                    <h3 class="mb-0"><?= $resumen['activos'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-danger mb-1">Inactivos</h6>
                    <h3 class="mb-0"><?= $resumen['inactivos'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-primary mb-1">Prioridad Normal</h6>
                    <h3 class="mb-0"><?= $resumen['normal'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-warning mb-1">Prioridad Alta</h6>
                    <h3 class="mb-0"><?= $resumen['alta'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-info mb-1">Prioridad Emergencia</h6>
                    <h3 class="mb-0"><?= $resumen['emergencia'] ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card content-card">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-4"><button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalRegistroServicio"><i class="fa-solid fa-plus me-2"></i>Nuevo Servicio</button></div>
                <div class="col-md-4 text-center">
                    <h5 class="mb-0 fw-bold">Lista de Servicios</h5>
                </div>
                <div class="col-md-4">
                    <form id="formBuscarServicios" class="search-box">
                        <div class="input-group"><input id="buscarServicios" type="search" class="form-control" placeholder="Buscar servicio..."><button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button></div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table id="tablaServiciosMvc" class="table table-hover table-modern align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody><?php if ($servicios): ?><?php foreach ($servicios as $indice => $servicio): ?><tr>
                            <td><?= $inicio + $indice + 1 ?></td>
                            <td><?= htmlspecialchars($servicio['nombre_serv']) ?></td>
                            <td><span class="badge badge-custom"><?= htmlspecialchars($servicio['codigo_serv']) ?></span></td>
                            <td><span class="badge badge-prioridad <?= strtolower($servicio['prioridad_serv']) ?>"><?= htmlspecialchars($servicio['prioridad_serv']) ?></span></td>
                            <td><span class="badge <?= (int) $servicio['estado_serv'] === 1 ? 'bg-success' : 'bg-danger' ?>"><?= (int) $servicio['estado_serv'] === 1 ? 'Activo' : 'Inactivo' ?></span></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center action-buttons"><button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarServicio<?= $servicio['id_servicios'] ?>" title="Editar Servicio">
                                        <i class="fa-solid fa-pen-to-square"></i></button>
                                    <form method="POST" action="<?= BASE_URL ?>index.php?ruta=servicios&action=estado" class="formCambiarEstadoServicio d-inline">
                                        <input type="hidden" name="id" value="<?= $servicio['id_servicios'] ?>">
                                        <input type="hidden" name="estado" value="<?= (int) $servicio['estado_serv'] === 1 ? 0 : 1 ?>">
                                        <button class="btn <?= (int) $servicio['estado_serv'] === 1 ? 'btn-danger' : 'btn-success' ?> btn-sm" type="submit" title="<?= (int) $servicio['estado_serv'] === 1 ? 'Desactivar' : 'Activar' ?>">
                                            <i class="fa-solid <?= (int) $servicio['estado_serv'] === 1 ? 'fa-circle-xmark' : 'fa-circle-check' ?>"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr><?php endforeach; ?><?php else: ?><tr>
                            <td colspan="6" class="text-center py-5 text-muted">No se encontraron servicios.</td>
                        </tr><?php endif; ?></tbody>
                </table>
            </div>
            <div class="d-none justify-content-between align-items-center mt-4">
                <div class="text-muted small">Mostrando <?= $desde ?> al <?= $hasta ?> de <?= $totalRegistros ?> servicios</div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>index.php?ruta=servicios&pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($buscar) ?>">Anterior</a>
                        </li><?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>index.php?ruta=servicios&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>"><?= $i ?></a>
                            </li><?php endfor; ?><li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>index.php?ruta=servicios&pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($buscar) ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php unset($servicio);
require __DIR__ . '/modal_crear.php';
foreach ($servicios as $servicio) require __DIR__ . '/modal_editar.php'; ?>
<script src="<?= BASE_URL ?>public/js/servicios-mvc.js"></script>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>