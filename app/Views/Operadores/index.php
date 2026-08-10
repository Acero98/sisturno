<?php
$cssModulo = 'operadores-mvc';
include __DIR__ . '/../Layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="page-header-card operator-header mb-4">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-header-icon"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <h4 class="mb-1">Gestión de Operadores</h4>
                        <p class="mb-0">Administra operadores, sus ventanillas y los servicios que atienden.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-end d-none d-lg-block"><i class="fa-solid fa-users-gear page-header-decoration"></i></div>
        </div>
    </div>

    <div class="card content-card border-0">
        <div class="card-body p-4">
            <div class="operators-toolbar mb-4">
                <div>
                    <button type="button" class="btn btn-new-operator" data-bs-toggle="modal" data-bs-target="#modalRegistroOperador">
                        <i class="fa-solid fa-user-plus me-2"></i>Nuevo Operador
                    </button>
                </div>
                <div class="operators-title">
                    <div class="operators-title-icon"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <h5 class="mb-0">Lista de Operadores</h5><small>Personal disponible para la atención</small>
                    </div>
                </div>
                <form id="formBuscarOperadores" class="operators-search">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="buscarOperadores" type="search" class="form-control" placeholder="Buscar operador..." value="">
                        <button class="btn btn-search" type="submit">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="tablaOperadoresMvc" class="table table-hover table-modern align-middle operators-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Operador</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>DNI</th>
                            <th>Ubicación</th>
                            <th>Ventanilla</th>
                            <th>Estado</th>
                            <th class="text-center">Servicios</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($operadores)): ?>
                            <?php foreach ($operadores as $indice => $operador): ?>
                                <?php $servicios = $operador['servicios_asignados'] ? explode('|', $operador['servicios_asignados']) : []; ?>
                                <tr>
                                    <td><span class="operator-id"><?= $inicio + $indice + 1 ?></span></td>
                                    <td>
                                        <div class="table-operator">
                                            <div class="table-operator-avatar"><i class="fa-solid fa-user-headset"></i></div>
                                            <div>
                                                <div class="table-operator-name"><?= htmlspecialchars($operador['nombre_user']) ?></div><small><?= htmlspecialchars($operador['puesto_user'] ?: 'Sin puesto asignado') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="username-text"><?= htmlspecialchars($operador['usuario_user']) ?></span></td>
                                    <td><span class="role-badge"><i class="fa-solid fa-shield-halved"></i><?= htmlspecialchars($operador['nombre_rol']) ?></span></td>
                                    <td><?= htmlspecialchars($operador['dni_user'] ?: '—') ?></td>
                                    <td>
                                        <div><?= htmlspecialchars($operador['oficina_user'] ?: 'Sin oficina') ?></div><small class="text-muted"><?= htmlspecialchars($operador['puesto_user'] ?: '') ?></small>
                                    </td>
                                    <td><span class="window-badge"><i class="fa-solid fa-window-maximize"></i><?= htmlspecialchars($operador['num_ventanilla'] ?: '—') ?></span></td>
                                    <td><span class="status-badge <?= (int) $operador['estado_user'] === 1 ? 'status-active' : 'status-inactive' ?>"><span class="status-dot"></span><?= (int) $operador['estado_user'] === 1 ? 'Activo' : 'Inactivo' ?></span></td>
                                    <td class="text-center"><button class="btn btn-action btn-services" type="button" data-bs-toggle="collapse" data-bs-target="#servicios<?= $operador['id_usuario'] ?>" aria-expanded="false" title="Ver servicios"><i class="fa-solid fa-list-check"></i><span class="visually-hidden">Ver servicios</span></button></td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center action-buttons"><a href="<?= BASE_URL ?>index.php?ruta=operadores-servicios&id_usuario=<?= $operador['id_usuario'] ?>" class="btn btn-action btn-assign" title="Asignar servicios"><i class="fa-solid fa-clipboard-list"></i></a><button class="btn btn-action btn-edit" type="button" data-bs-toggle="modal" data-bs-target="#modalEditarOperador<?= $operador['id_usuario'] ?>" title="Editar operador"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <form method="POST" action="<?= BASE_URL ?>index.php?ruta=operadores&action=estado" class="formCambiarEstadoOperador d-inline"><input type="hidden" name="id" value="<?= $operador['id_usuario'] ?>"><input type="hidden" name="estado" value="<?= (int) $operador['estado_user'] === 1 ? 0 : 1 ?>"><button class="btn btn-action <?= (int) $operador['estado_user'] === 1 ? 'btn-disable' : 'btn-enable' ?>" type="submit" title="<?= (int) $operador['estado_user'] === 1 ? 'Desactivar' : 'Activar' ?>"><i class="fa-solid <?= (int) $operador['estado_user'] === 1 ? 'fa-user-slash' : 'fa-user-check' ?>"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="operator-services-row">
                                    <td colspan="10" class="p-0 border-0">
                                        <div class="collapse" id="servicios<?= $operador['id_usuario'] ?>">
                                            <div class="operator-services"><i class="fa-solid fa-list-check me-2"></i><strong>Servicios asignados:</strong><?php if ($servicios): ?><?php foreach ($servicios as $servicio): ?><span class="service-chip"><?= htmlspecialchars($servicio) ?></span><?php endforeach; ?><?php else: ?><span class="text-muted ms-2">No tiene servicios asignados.</span><?php endif; ?></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted"><i class="fa-solid fa-users-slash fa-2x mb-3 d-block"></i>No se encontraron operadores.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="operators-pagination mt-4 d-none">
                <div class="text-muted small">Mostrando <strong><?= $desde ?></strong> al <strong><?= $hasta ?></strong> de <strong><?= $totalRegistros ?></strong> registros</div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= BASE_URL ?>index.php?ruta=operadores&pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($buscar) ?>">Anterior</a></li>
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?><li class="page-item <?= $i === $pagina ? 'active' : '' ?>"><a class="page-link" href="<?= BASE_URL ?>index.php?ruta=operadores&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>"><?= $i ?></a></li><?php endfor; ?>
                        <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>"><a class="page-link" href="<?= BASE_URL ?>index.php?ruta=operadores&pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($buscar) ?>">Siguiente</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php unset($operador); ?>
<?php require __DIR__ . '/modal_crear.php'; ?>
<?php foreach ($operadores as $operador): ?>
    <?php require __DIR__ . '/modal_editar.php'; ?>
<?php endforeach; ?>

<script src="<?= BASE_URL ?>public/js/operadores-mvc.js"></script>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>
