<?php
require_once __DIR__ . "/../modelo/conexion.php";
require_once __DIR__ . "/../control/auth.php";
require_once __DIR__ . "/../control/permisos.php";

permitirSolo(["Super Admin"]);

include_once __DIR__ . "/../controlador/eliminar_usuario.php";
include_once __DIR__ . "/../controlador/registrar_usuario.php";
include_once __DIR__ . "/../controlador/modificar_usuario.php";
include "header.php";
?>

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="page-header-card mb-3 py-2">
        <div class="row align-items-center">

            <div class="col-lg-9">
                <h4 class="mb-1">
                    <i class="fa-solid fa-users-cog me-2"></i>
                    Gestión de Usuarios
                </h4>

                <p class="mb-0">
                    Administra los usuarios del sistema, asigna roles y controla el acceso.
                </p>
            </div>

            <div class="col-lg-3 text-end d-none d-lg-block">
                <i class="fa-solid fa-user-shield"
                    style="font-size: 3.5rem; opacity: 0.12;"></i>
            </div>

        </div>
    </div>

    <!-- Tarjeta principal -->
    <div class="card content-card">
        <div class="card-body p-4">

            <!-- Barra superior -->
            <div class="row g-3 align-items-center mb-4">

                <!-- Botón Nuevo Usuario -->
                <div class="col-md-4">
                    <button type="button"
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#modalRegistro">
                        <i class="fa-solid fa-plus me-2"></i>
                        Nuevo Usuario
                    </button>
                </div>

                <!-- Título -->
                <div class="col-md-4 text-center">
                    <h5 class="mb-0 fw-bold">Lista de Usuarios</h5>
                </div>

                <!-- Buscador -->
                <div class="col-md-4">
                    <form method="GET" class="search-box">
                        <div class="input-group">
                            <input type="text"
                                name="buscar"
                                class="form-control"
                                placeholder="Buscar usuario..."
                                value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">

                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover table-modern align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="d-flex gap-2 justify-content-center action-buttons">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $registrosPorPagina = 10;

                        // Página actual
                        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                        if ($pagina < 1) $pagina = 1;

                        // Calcular inicio
                        $inicio = ($pagina - 1) * $registrosPorPagina;

                        //BUSCAR REGISTRO
                        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
                        $where = "";

                        if (!empty($buscar)) {
                            $buscar = $conexion->real_escape_string($buscar);
                            $where = "WHERE u.nombre_user LIKE '%$buscar%' 
                                        OR u.usuario_user LIKE '%$buscar%'
                                        OR r.nombre_rol LIKE '%$buscar%'
                                        OR u.estado_user LIKE '%$buscar%'
                                        ";
                        }

                        // Contar total registros
                        $totalRegistrosQuery = $conexion->query("
                            SELECT COUNT(*) as total
                            FROM usuarios u
                            INNER JOIN roles r ON r.id_rol = u.id_rol_user
                            $where
                        ");

                        $totalRegistros = $totalRegistrosQuery->fetch_object()->total;

                        // Total páginas
                        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

                        // Obtener registros
                        $sql = $conexion->query("
                            SELECT u.*, r.nombre_rol
                            FROM usuarios u
                            INNER JOIN roles r ON u.id_rol_user = r.id_rol
                            $where
                            ORDER BY u.nombre_user ASC
                            LIMIT $inicio, $registrosPorPagina
                        ");

                        $sql = $conexion->query("SELECT u.*, r.nombre_rol 
                                                    FROM usuarios u
                                                    INNER JOIN roles r ON u.id_rol_user = r.id_rol
                                                    $where                                                    
                                                    ORDER BY u.nombre_user ASC
                                                    LIMIT $inicio, $registrosPorPagina
                                                ");

                        $contador = $inicio + 1;

                        while ($datos = $sql->fetch_object()) { ?>

                            <tr>
                                <td><?= $contador++ ?></td>
                                <td><?= $datos->nombre_user ?></td>
                                <td><?= $datos->usuario_user ?></td>
                                <td><?= $datos->nombre_rol ?></td>
                                <td>
                                    <span class="badge <?= $datos->estado_user == 1 ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $datos->estado_user == 1 ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center action-buttons">

                                        <!-- Editar -->
                                        <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar<?= $datos->id_usuario ?>"
                                            title="Editar Usuario">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Activar / Desactivar -->
                                        <?php if ($datos->estado_user == 1): ?>
                                            <a href="#"
                                                class="btn btn-danger btn-sm btnDesactivar"
                                                data-id="<?= $datos->id_usuario ?>"
                                                title="Desactivar Usuario">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="#"
                                                class="btn btn-success btn-sm btnActivar"
                                                data-id="<?= $datos->id_usuario ?>"
                                                title="Activar Usuario">
                                                <i class="fa-solid fa-user-check"></i>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL EDITAR -->
                            <div class="modal fade" id="modalEditar<?= $datos->id_usuario ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <form method="POST" class="formEditarUsuario">

                                                <input type="hidden" name="id" value="<?= $datos->id_usuario ?>">
                                                <!-- <input type="hidden" name="btnmodificarUsuario" value="ok"> -->

                                                <div class="mb-3">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" name="nombre"
                                                        value="<?= $datos->nombre_user ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Usuario</label>
                                                    <input type="text" class="form-control" name="usuario"
                                                        value="<?= $datos->usuario_user ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Nueva Contraseña</label>
                                                    <input type="password" class="form-control" name="password">
                                                    <small class="text-muted">Dejar vacío si no desea cambiarla</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Rol</label>
                                                    <select name="rol" class="form-select" required>

                                                        <?php
                                                        $roles = $conexion->query("SELECT * FROM roles WHERE estado_rol = 1");

                                                        while ($rol = $roles->fetch_object()) {
                                                        ?>

                                                            <option value="<?= $rol->id_rol ?>"
                                                                <?= ($rol->id_rol == $datos->id_rol_user) ? 'selected' : '' ?>>
                                                                <?= $rol->nombre_rol ?>
                                                            </option>

                                                        <?php } ?>

                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-warning">
                                                    Guardar Cambios
                                                </button>

                                            </form>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                    </tbody>
                </table>
                <?php
                $desde = ($totalRegistros > 0) ? $inicio + 1 : 0;
                $hasta = min($inicio + $registrosPorPagina, $totalRegistros);
                ?>

                <div class="d-flex justify-content-between align-items-center mt-4">

                    <div class="text-muted small">
                        Mostrando <?= $desde ?> al <?= $hasta ?> de <?= $totalRegistros ?> registros
                    </div>

                    <nav>
                        <ul class="pagination mb-0">

                            <!-- Botón anterior -->
                            <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($buscar) ?>">
                                    Anterior
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                    <a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Botón siguiente -->
                            <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($buscar) ?>">
                                    Siguiente
                                </a>
                            </li>

                        </ul>
                    </nav>

                </div>
            </div>

        </div>
    </div>

</div>
</div>

</div>

<!-- BOTÓN FLOTANTE -->
<button type="button"
    class="btn btn-primary floating-btn"
    data-bs-toggle="modal"
    data-bs-target="#modalRegistro"
    title="Registrar Usuario">
    <i class="fa-solid fa-plus"></i>
</button>

<!-- MODAL -->
<div class="modal fade" id="modalRegistro" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Usuario</h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" class="formRegistrarUsuario">
                    <input type="hidden" name="btnregistrarUsuario" value="ok">

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <!-- NUEVO CAMPO ROL -->
                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select" required>
                            <option value="">Seleccionar rol</option>

                            <?php
                            $roles = $conexion->query("SELECT * FROM roles WHERE estado_rol = 1");

                            while ($rol = $roles->fetch_object()) {
                            ?>
                                <option value="<?= $rol->id_rol ?>">
                                    <?= $rol->nombre_rol ?>
                                </option>
                            <?php } ?>

                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="button"
                            class="btn btn-primary btnConfirmarRegistro">
                            Registrar
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