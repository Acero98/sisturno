<?php
require_once __DIR__ . "/../modelo/conexion.php";
require_once __DIR__ . "/../control/auth.php";
require_once __DIR__ . "/../control/permisos.php";
permitirSolo(["Super Admin"]);
include_once __DIR__ . "/../controlador/eliminar_usuario.php";
include_once __DIR__ . "/../controlador/registrar_usuario.php";
include_once __DIR__ . "/../controlador/modificar_usuario.php";
$cssModulo = 'usuarios';
include "header.php";
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
    <!-- Tarjeta principal -->
    <div class="card content-card border-0">
        <div class="card-body p-4">
            <!-- Barra superior -->
            <div class="users-toolbar mb-4">
                <!-- Botón -->
                <div>
                    <button type="button"
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
                <form method="GET" class="users-search">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Buscar usuario..."
                            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                        <button class="btn btn-search" type="submit">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table table-hover table-modern align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $registrosPorPagina = 10;
                        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                        if ($pagina < 1) {
                            $pagina = 1;
                        }
                        $inicio = ($pagina - 1) * $registrosPorPagina;
                        $buscar = isset($_GET['buscar'])
                            ? trim($_GET['buscar'])
                            : "";
                        $where = "";
                        if (!empty($buscar)) {
                            $buscar = $conexion->real_escape_string($buscar);
                            $where = "WHERE u.nombre_user LIKE '%$buscar%' 
                                    OR u.usuario_user LIKE '%$buscar%'
                                    OR r.nombre_rol LIKE '%$buscar%'
                                    OR u.estado_user LIKE '%$buscar%'";
                        }
                        // Contar registros
                        $totalRegistrosQuery = $conexion->query("
                        SELECT COUNT(*) as total
                        FROM usuarios u
                        INNER JOIN roles r ON r.id_rol = u.id_rol_user
                        $where
                    ");
                        $totalRegistros = $totalRegistrosQuery
                            ->fetch_object()
                            ->total;
                        $totalPaginas = ceil(
                            $totalRegistros / $registrosPorPagina
                        );
                        // Obtener registros
                        $sql = $conexion->query("
                        SELECT 
                            u.*, 
                            r.nombre_rol
                        FROM usuarios u
                        INNER JOIN roles r 
                            ON u.id_rol_user = r.id_rol
                        $where
                        ORDER BY u.nombre_user ASC
                        LIMIT $inicio, $registrosPorPagina
                    ");
                        $contador = $inicio + 1;
                        while ($datos = $sql->fetch_object()) {
                        ?>
                            <tr>
                                <td>
                                    <span class="user-id">
                                        <?= $contador++ ?>
                                    </span>
                                </td>
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
                                <td>
                                    <span class="username-text">
                                        <?= htmlspecialchars($datos->usuario_user) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="role-badge">
                                        <i class="fa-solid fa-shield-halved"></i>
                                        <?= htmlspecialchars($datos->nombre_rol) ?>
                                    </span>
                                </td>
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
                                <td>
                                    <div class="d-flex gap-2 justify-content-center action-buttons">
                                        <!-- Editar -->
                                        <button class="btn btn-action btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar<?= $datos->id_usuario ?>"
                                            title="Editar Usuario">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- Activar / Desactivar -->
                                        <?php if ($datos->estado_user == 1): ?>
                                            <a href="#"
                                                class="btn btn-action btn-disable btnDesactivar"
                                                data-id="<?= $datos->id_usuario ?>"
                                                title="Desactivar Usuario">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="#"
                                                class="btn btn-action btn-enable btnActivar"
                                                data-id="<?= $datos->id_usuario ?>"
                                                title="Activar Usuario">
                                                <i class="fa-solid fa-user-check"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <!-- MODAL EDITAR -->
                            <div class="modal fade"
                                id="modalEditar<?= $datos->id_usuario ?>"
                                tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content user-modal">
                                        <div class="modal-header">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="modal-icon">
                                                    <i class="fa-solid fa-user-pen"></i>
                                                </div>
                                                <div>
                                                    <h5 class="modal-title mb-0">
                                                        Editar Usuario
                                                    </h5>
                                                    <small>
                                                        Modifica los datos de acceso
                                                    </small>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST"
                                                class="formEditarUsuario">
                                                <input type="hidden"
                                                    name="id"
                                                    value="<?= $datos->id_usuario ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Nombre
                                                    </label>
                                                    <div class="input-icon-wrapper">
                                                        <i class="fa-solid fa-user"></i>
                                                        <input type="text"
                                                            class="form-control"
                                                            name="nombre"
                                                            value="<?= htmlspecialchars($datos->nombre_user) ?>"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Usuario
                                                    </label>
                                                    <div class="input-icon-wrapper">
                                                        <i class="fa-solid fa-at"></i>
                                                        <input type="text"
                                                            class="form-control"
                                                            name="usuario"
                                                            value="<?= htmlspecialchars($datos->usuario_user) ?>"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Nueva Contraseña
                                                    </label>
                                                    <div class="input-icon-wrapper">
                                                        <i class="fa-solid fa-lock"></i>
                                                        <input type="password"
                                                            class="form-control"
                                                            name="password">
                                                    </div>
                                                    <small class="form-text">
                                                        Dejar vacío si no desea cambiarla.
                                                    </small>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label">
                                                        Rol
                                                    </label>
                                                    <div class="input-icon-wrapper">
                                                        <i class="fa-solid fa-shield-halved"></i>
                                                        <select name="rol"
                                                            class="form-select"
                                                            required>
                                                            <?php
                                                            $roles = $conexion->query("
                                                            SELECT *
                                                            FROM roles
                                                            WHERE estado_rol = 1
                                                        ");
                                                            while ($rol = $roles->fetch_object()) {
                                                            ?>
                                                                <option
                                                                    value="<?= $rol->id_rol ?>"
                                                                    <?= ($rol->id_rol == $datos->id_rol_user)
                                                                        ? 'selected'
                                                                        : '' ?>>
                                                                    <?= htmlspecialchars($rol->nombre_rol) ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer-custom">
                                                    <button type="button"
                                                        class="btn btn-modal-cancel"
                                                        data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit"
                                                        class="btn btn-modal-save">
                                                        <i class="fa-solid fa-check me-2"></i>
                                                        Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                $desde = ($totalRegistros > 0)
                    ? $inicio + 1
                    : 0;
                $hasta = min(
                    $inicio + $registrosPorPagina,
                    $totalRegistros
                );
                ?>
                <!-- Paginación -->
                <div class="users-pagination mt-4">
                    <div class="text-muted small">
                        Mostrando
                        <strong><?= $desde ?></strong>
                        al
                        <strong><?= $hasta ?></strong>
                        de
                        <strong><?= $totalRegistros ?></strong>
                        registros
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($buscar) ?>">
                                    <i class="fa-solid fa-chevron-left"></i>
                                    Anterior
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($buscar) ?>">
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
</div>
<!-- MODAL REGISTRAR USUARIO -->
<div class="modal fade" id="modalRegistro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content user-modal">
            <!-- Encabezado -->
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-icon modal-icon-create">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">
                            Registrar Usuario
                        </h5>
                        <small>
                            Crea un nuevo usuario para el sistema
                        </small>
                    </div>
                </div>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <!-- Cuerpo -->
            <div class="modal-body">
                <form method="POST"
                    class="formRegistrarUsuario">
                    <input type="hidden"
                        name="btnregistrarUsuario"
                        value="ok">
                    <!-- Nombre -->
                    <div class="mb-3">
                        <label class="form-label">
                            Nombre
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-user"></i>
                            <input type="text"
                                class="form-control"
                                name="nombre"
                                placeholder="Nombre completo"
                                autocomplete="name"
                                required>
                        </div>
                    </div>
                    <!-- Usuario -->
                    <div class="mb-3">
                        <label class="form-label">
                            Usuario
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-at"></i>
                            <input type="text"
                                class="form-control"
                                name="usuario"
                                placeholder="Nombre de usuario"
                                autocomplete="username"
                                required>
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            Contraseña
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password"
                                class="form-control"
                                name="password"
                                placeholder="Contraseña"
                                autocomplete="new-password"
                                required>
                        </div>
                    </div>
                    <!-- Rol -->
                    <div class="mb-4">
                        <label class="form-label">
                            Rol
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-shield-halved"></i>
                            <select name="rol"
                                class="form-select"
                                required>
                                <option value="">
                                    Seleccionar rol
                                </option>
                                <?php
                                $roles = $conexion->query("
                                    SELECT *
                                    FROM roles
                                    WHERE estado_rol = 1
                                ");
                                while ($rol = $roles->fetch_object()) {
                                ?>
                                    <option value="<?= $rol->id_rol ?>">
                                        <?= htmlspecialchars($rol->nombre_rol) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="modal-footer-custom">
                        <button type="button"
                            class="btn btn-modal-cancel"
                            data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button"
                            class="btn btn-modal-create btnConfirmarRegistro">
                            <i class="fa-solid fa-user-plus me-2"></i>
                            Registrar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>public/js/alertas.js"></script>
<?php
include "footer.php";
?>