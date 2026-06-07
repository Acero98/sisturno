<?php
require_once __DIR__ . "/../modelo/conexion.php";
require_once __DIR__ . "/../control/auth.php";
require_once __DIR__ . "/../control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

include_once __DIR__ . "/../controlador/eliminar_operador.php";
include_once __DIR__ . "/../controlador/registrar_operador.php";
include_once __DIR__ . "/../controlador/modificar_operador.php";
include "header.php";
?>

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="page-header-card mb-3 py-2">
        <div class="row align-items-center">

            <div class="col-lg-9">
                <h4 class="mb-1">
                    <i class="fa-solid fa-users-gear me-2"></i>
                    Gestión de Operadores
                </h4>

                <p class="mb-0">
                    Administra operadores, asigna servicios y controla el acceso al sistema.
                </p>
            </div>

            <div class="col-lg-3 text-end d-none d-lg-block">
                <i class="fa-solid fa-headset"
                    style="font-size: 3.5rem; opacity: 0.12;"></i>
            </div>

        </div>
    </div>

    <!-- Tarjeta principal -->
    <div class="card content-card">
        <div class="card-body p-4">

            <!-- Barra superior -->
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-4">
                    <button type="button"
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#modalRegistro">
                        <i class="fa-solid fa-plus me-2"></i>
                        Nuevo Operador
                    </button>
                </div>

                <div class="col-md-4 text-center">
                    <h5 class="mb-0 fw-bold">Lista de Operadores</h5>
                </div>

                <div class="col-md-4">
                    <form method="GET" class="search-box">
                        <div class="input-group">
                            <input type="text"
                                name="buscar"
                                class="form-control"
                                placeholder="Buscar operador..."
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
                            <th>DNI</th>
                            <th>Puesto</th>
                            <th>Oficina</th>
                            <th>Ventanilla</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
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

                        $filtro = "";

                        if (!empty($buscar)) {
                            $buscar = $conexion->real_escape_string($buscar);
                            $filtro = " AND (
                                u.nombre_user LIKE '%$buscar%'
                                OR u.usuario_user LIKE '%$buscar%'
                            )";
                        }

                        $totalRegistrosQuery = $conexion->query("
                            SELECT COUNT(*) as total
                            FROM usuarios u
                            WHERE (u.id_rol_user = 3 OR u.id_rol_user = 2)
                            $filtro
                        ");

                        $totalRegistros = $totalRegistrosQuery->fetch_object()->total;

                        // Total páginas
                        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);


                        $sql = $conexion->query("
                            SELECT u.*, r.nombre_rol
                            FROM usuarios u
                            INNER JOIN roles r ON u.id_rol_user = r.id_rol
                            WHERE (u.id_rol_user = 3 OR u.id_rol_user = 2)
                            $filtro
                            ORDER BY u.nombre_user ASC
                            LIMIT $inicio, $registrosPorPagina
                        ");

                        // Antes del while, inicializamos el contador según la página actual
                        $contador = $inicio + 1;

                        while ($datos = $sql->fetch_object()) { ?>

                            <tr>
                                <td><?= $contador++ ?></td>
                                <td><?= $datos->nombre_user ?></td>
                                <td><?= $datos->usuario_user ?></td>
                                <td><?= $datos->nombre_rol ?></td>
                                <td><?= $datos->dni_user ?></td>
                                <td><?= $datos->puesto_user ?></td>
                                <td><?= $datos->oficina_user ?></td>
                                <td><?= $datos->num_ventanilla ?></td>
                                <td>
                                    <span class="badge <?= $datos->estado_user == 1 ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $datos->estado_user == 1 ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center action-buttons">

                                        <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar<?= $datos->id_usuario ?>"
                                            title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <a href="servuser/index.php?id_usuario=<?= $datos->id_usuario ?>"
                                            class="btn btn-primary btn-sm"
                                            title="Asignar servicios">
                                            <i class="fa-solid fa-list-check"></i>
                                        </a>

                                        <?php if ($datos->estado_user == 1): ?>
                                            <a href="#"
                                                class="btn btn-danger btn-sm btnDesactivarOpe"
                                                data-id="<?= $datos->id_usuario ?>"
                                                title="Desactivar">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="#"
                                                class="btn btn-success btn-sm btnActivarOpe"
                                                data-id="<?= $datos->id_usuario ?>"
                                                title="Activar">
                                                <i class="fa-solid fa-user-check"></i>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL EDITAR OPERADORES -->
                            <div class="modal fade" id="modalEditar<?= $datos->id_usuario ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Operador</h5>
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

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">DNI</label>
                                                        <input type="text" class="form-control" name="dni" value="<?= $datos->dni_user ?>" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Género</label>
                                                        <select name="genero" class="form-select" required>
                                                            <option value="">Seleccione</option>
                                                            <option value="M" <?= ($datos->genero_user == 'M') ? 'selected' : '' ?>>Masculino</option>
                                                            <option value="F" <?= ($datos->genero_user == 'F') ? 'selected' : '' ?>>Femenino</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Puesto</label>
                                                    <input type="text" class="form-control" name="puesto" value="<?= $datos->puesto_user ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Oficina</label>
                                                    <input type="text" class="form-control" name="oficina" value="<?= $datos->oficina_user ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Observaciones</label>
                                                    <textarea
                                                        class="form-control"
                                                        name="observaciones"
                                                        rows="2"><?= htmlspecialchars($datos->observaciones_user ?? '') ?></textarea>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Usuario</label>
                                                        <input type="text" class="form-control" name="usuario"
                                                            value="<?= $datos->usuario_user ?>"
                                                            readonly
                                                            style="background-color: #e9ecef; cursor: not-allowed;">

                                                        <small class="text-muted">
                                                            El usuario no puede ser modificado.
                                                        </small>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nueva Contraseña</label>
                                                        <input type="password" class="form-control" name="password">
                                                        <small class="text-muted">Dejar vacío si no desea cambiarla</small>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Rol</label>

                                                        <select name="rol" class="form-select" required>
                                                            <option value="2" <?= $datos->id_rol_user == 2 ? 'selected' : '' ?>>
                                                                Admin
                                                            </option>

                                                            <option value="3" <?= $datos->id_rol_user == 3 ? 'selected' : '' ?>>
                                                                Operador
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Ventanilla</label>
                                                        <input type="text" class="form-control" name="ventanilla" value="<?= $datos->num_ventanilla ?>" required>
                                                    </div>
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
    title="Registrar Operador">
    <i class="fa-solid fa-plus"></i>
</button>

<!-- MODAL - REGISTRAR OPERADOR -->
<div class="modal fade" id="modalRegistro" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Operador</h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" class="formRegistrarUsuario">
                    <input type="hidden" name="btnregistrarUsuario" value="ok">

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Escribe aquí nombres y apellidos del operador." required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" name="dni" placeholder="Escribe aquí el DNI." required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Genero</label>
                            <select name="genero" class="form-select" required>
                                <option value="">Seleccionar rol</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Puesto</label>
                        <input type="text" class="form-control" name="puesto" placeholder="Ejemplo: Ejecutivo de Atencion al Cliente." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Oficina</label>
                        <input type="text" class="form-control" name="oficina" placeholder="Escribe aquí el nombre de la oficina." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea
                            class="form-control"
                            name="observaciones"
                            rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="usuario" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <!-- NUEVO CAMPO ROL -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol</label>

                            <select name="rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>

                                <?php
                                $roles = $conexion->query("
                                    SELECT id_rol, nombre_rol
                                    FROM roles
                                    WHERE id_rol IN (2,3)
                                    ORDER BY nombre_rol
                                ");

                                while ($rol = $roles->fetch_object()) {
                                ?>
                                    <option value="<?= $rol->id_rol ?>">
                                        <?= $rol->nombre_rol ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ventanilla</label>
                            <input type="text" class="form-control" name="ventanilla" placeholder="Escriba el número." required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="button"
                            class="btn btn-primary btnConfirmarRegistroOpe">
                            Registrar
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>public/js/operadores.js"></script>

<?php
include "footer.php";
?>