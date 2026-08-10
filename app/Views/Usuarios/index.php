<?php
$cssModulo = 'usuarios';
include __DIR__ . '/../Layouts/header.php';
?>
<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="page-header-card mb-4">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center gap-3">
                    <div class="page-header-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">
                            Gestión de Usuarios
                        </h4>
                        <p class="mb-0">
                            Administra los usuarios del sistema, asigna roles y controla el acceso.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-end d-none d-lg-block">
                <i class="fa-solid fa-user-shield page-header-decoration"></i>
            </div>
        </div>
    </div>
    <div class="card content-card border-0">
        <div class="card-body p-4">
            <!-- ==========================================
             BARRA SUPERIOR
        =========================================== -->
            <div class="users-toolbar mb-4">
                <!-- Botón nuevo usuario -->
                <div>
                    <button
                        type="button"
                        class="btn btn-new-user"
                        data-bs-toggle="modal"
                        data-bs-target="#modalRegistro">
                        <i class="fa-solid fa-plus me-2"></i>
                        Nuevo Usuario
                    </button>
                </div>
                <!-- Título -->
                <div class="users-title">
                    <div class="users-title-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">
                            Lista de Usuarios
                        </h5>
                        <small>
                            Usuarios registrados en el sistema
                        </small>
                    </div>
                </div>
                <!-- Buscador -->
                <form
                    id="formBuscarUsuarios"
                    class="users-search">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input
                            id="buscarUsuarios"
                            type="search"
                            class="form-control"
                            placeholder="Buscar usuario..."
                            value="">
                        <button
                            class="btn btn-search"
                            type="submit">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
            <!-- ==========================================
             TABLA DE USUARIOS
        =========================================== -->
            <div class="table-responsive">
                <table id="tablaUsuariosMvc" class="table table-hover table-modern align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php
                            $contador = $inicio + 1;
                            foreach ($usuarios as $usuario):
                                $datos = (object) $usuario;
                            ?>
                                <tr>
                                    <!-- ID -->
                                    <td>
                                        <span class="user-id">
                                            <?= $contador++ ?>
                                        </span>
                                    </td>
                                    <!-- NOMBRE -->
                                    <td>
                                        <div class="table-user">
                                            <div class="table-user-avatar">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="table-user-name">
                                                    <?= htmlspecialchars($datos->nombre_user) ?>
                                                </div>
                                                <small>
                                                    Usuario del sistema
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- USUARIO -->
                                    <td>
                                        <span class="username-text">
                                            <?= htmlspecialchars($datos->usuario_user) ?>
                                        </span>
                                    </td>
                                    <!-- ROL -->
                                    <td>
                                        <span class="role-badge">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            <?= htmlspecialchars($datos->nombre_rol) ?>
                                        </span>
                                    </td>
                                    <!-- ESTADO -->
                                    <td>
                                        <?php if ($datos->estado_user == 1): ?>
                                            <span class="status-badge status-active">
                                                <span class="status-dot"></span>
                                                Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">
                                                <span class="status-dot"></span>
                                                Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- ACCIONES -->
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center action-buttons">
                                            <!-- Editar -->
                                            <button
                                                type="button"
                                                class="btn btn-action btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditar<?= $datos->id_usuario ?>"
                                                title="Editar Usuario">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <!-- Activar / Desactivar -->
                                            <?php if ($datos->estado_user == 1): ?>
                                                <a
                                                    href="<?= BASE_URL ?>index.php?ruta=usuarios&action=estado&id=<?= $datos->id_usuario ?>&estado=0"
                                                    class="btn btn-action btn-disable"
                                                    title="Desactivar Usuario">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a
                                                    href="<?= BASE_URL ?>index.php?ruta=usuarios&action=estado&id=<?= $datos->id_usuario ?>&estado=1"
                                                    class="btn btn-action btn-enable"
                                                    title="Activar Usuario">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td
                                    colspan="6"
                                    class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-solid fa-users-slash fa-2x mb-3"></i>
                                        <div>
                                            No se encontraron usuarios.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- ==========================================
             MODALES EDITAR USUARIO
        =========================================== -->
            <?php if (!empty($usuarios)): ?>
                <?php
                /*
             * IMPORTANTE:
             * Como $usuarios es un mysqli_result, ya fue recorrido
             * arriba. Por eso, posteriormente debemos obtener los
             * usuarios de otra forma si queremos generar los modales.
             *
             * La mejor solución será que el Model entregue un array
             * de usuarios en lugar de un mysqli_result.
             */
                foreach ($usuarios as $usuario) {
                    $datos = (object) $usuario;
                    require __DIR__ . '/modal_editar.php';
                }
                ?>
            <?php endif; ?>
            <!-- ==========================================
             PAGINACIÓN
        =========================================== -->
            <div class="users-pagination mt-4 d-none">
                <!-- Información -->
                <div class="text-muted small">
                    Mostrando
                    <strong>
                        <?= $desde ?>
                    </strong>
                    al
                    <strong>
                        <?= $hasta ?>
                    </strong>
                    de
                    <strong>
                        <?= $totalRegistros ?>
                    </strong>
                    registros
                </div>
                <!-- Navegación -->
                <nav>
                    <ul class="pagination mb-0">
                        <!-- Anterior -->
                        <li
                            class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                            <a
                                class="page-link"
                                href="<?= BASE_URL ?>index.php?ruta=usuarios&pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($buscar) ?>">
                                <i class="fa-solid fa-chevron-left"></i>
                                Anterior
                            </a>
                        </li>
                        <!-- Páginas -->
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li
                                class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                <a
                                    class="page-link"
                                    href="<?= BASE_URL ?>index.php?ruta=usuarios&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <!-- Siguiente -->
                        <li
                            class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                            <a
                                class="page-link"
                                href="<?= BASE_URL ?>index.php?ruta=usuarios&pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($buscar) ?>">
                                Siguiente
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/modal_crear.php'; ?>
<script src="<?= BASE_URL ?>public/js/usuarios-mvc.js"></script>
<?php
include __DIR__ . '/../Layouts/footer.php';
?>
