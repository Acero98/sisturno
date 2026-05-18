<?php
include "../../modelo/conexion.php";
include "../../control/auth.php";
include "../../control/permisos.php";
//include "../../controlador/servuser/guardar.php";

permitirSolo(["Super Admin", "Admin"]);

/*
|--------------------------------------------------------------------------
| 1. Obtener el ID del usuario enviado por la URL
|--------------------------------------------------------------------------
| Ejemplo:
| operador_servicios/index.php?id_usuario=3
*/
$id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;

if ($id_usuario <= 0) {
    die("Usuario no válido.");
}

/*
|--------------------------------------------------------------------------
| 2. Obtener los datos del usuario
|--------------------------------------------------------------------------
*/
$sqlUsuario = $conexion->query("
    SELECT *
    FROM usuarios
    WHERE id_usuario = $id_usuario
");

$usuario = $sqlUsuario->fetch_object();

if (!$usuario) {
    die("Usuario no encontrado.");
}

/*
|--------------------------------------------------------------------------
| 3. Obtener todos los servicios activos
|--------------------------------------------------------------------------
*/
$sqlServicios = $conexion->query("
    SELECT *
    FROM servicios
    WHERE estado_serv = 1
    ORDER BY nombre_serv ASC
");

/*
|--------------------------------------------------------------------------
| 4. Obtener los servicios ya asignados al usuario
|--------------------------------------------------------------------------
*/
$asignados = [];

$sqlAsignados = $conexion->query("
    SELECT id_servicio
    FROM operador_servicios
    WHERE id_usuario = $id_usuario
");

while ($fila = $sqlAsignados->fetch_assoc()) {
    $asignados[] = $fila['id_servicio'];
}

/*
|--------------------------------------------------------------------------
| 5. Incluir el header
|--------------------------------------------------------------------------
*/
include "../header.php";
?>
<style>
    .servicio-card {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .servicio-card:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="page-header-card mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2>
                    <i class="fa-solid fa-list-check me-2"></i>
                    Asignación de Servicios
                </h2>
                <p>
                    Selecciona los servicios que estarán disponibles para el operador.
                </p>
            </div>

            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="fa-solid fa-user-gear"
                    style="font-size: 4.5rem; opacity: 0.15;"></i>
            </div>
        </div>
    </div>

    <!-- Tarjeta principal -->
    <div class="card content-card">
        <div class="card-body p-4">

            <!-- Información del usuario -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-user text-primary me-2"></i>
                        <?= htmlspecialchars($usuario->nombre_user) ?>
                    </h5>
                    <p class="text-muted mb-0">
                        Configura los servicios asignados a este operador.
                    </p>
                </div>

                <div class="mt-3 mt-lg-0">
                    <a href="../lista_operadores.php"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <form method="POST"
                action="../../controlador/servuser/guardar.php"
                class="formGuardarAsignaciones">

                <!-- ID del usuario -->
                <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                <?php if ($sqlServicios->num_rows > 0): ?>

                    <!-- Grid de servicios -->
                    <div class="row g-4">

                        <?php while ($servicio = $sqlServicios->fetch_object()): ?>

                            <div class="col-md-6 col-lg-4">
                                <label class="w-100 h-100">

                                    <input type="checkbox"
                                        name="servicios[]"
                                        value="<?= $servicio->id_servicios ?>"
                                        class="d-none servicio-checkbox"
                                        <?= in_array($servicio->id_servicios, $asignados) ? 'checked' : '' ?>>

                                    <div class="card servicio-card h-100 shadow-sm border-2
                                        <?= in_array($servicio->id_servicios, $asignados)
                                            ? 'bg-primary text-white border-primary'
                                            : 'border-light-subtle' ?>">

                                        <div class="card-body text-center py-4">

                                            <div class="mb-3">
                                                <i class="fa-solid fa-concierge-bell fs-2"></i>
                                            </div>

                                            <h6 class="fw-bold mb-2">
                                                <?= htmlspecialchars($servicio->nombre_serv) ?>
                                            </h6>

                                            <span class="badge <?= in_array($servicio->id_servicios, $asignados)
                                                                    ? 'bg-light text-primary'
                                                                    : 'bg-primary-subtle text-primary' ?>">
                                                <?= htmlspecialchars($servicio->codigo_serv) ?>
                                            </span>

                                        </div>
                                    </div>

                                </label>
                            </div>

                        <?php endwhile; ?>

                    </div>

                <?php else: ?>

                    <div class="alert alert-warning border-0 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        No existen servicios activos para asignar.
                    </div>

                <?php endif; ?>

                <!-- Botón Guardar -->
                <div class="border-top mt-5 pt-4 text-center">
                    <button type="submit"
                        class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm btnGuardarAsignaciones">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Guardar Asignaciones
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="<?= BASE_URL ?>public/js/servuser.js"></script>

<?php include "../footer.php"; ?>