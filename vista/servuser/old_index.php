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

<div class="container-fluid mt-4">

    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-list-check"></i>
                        Asignar Servicios al Usuario:
                        <strong><?= htmlspecialchars($usuario->nombre_user) ?></strong>
                    </h5>

                    <a href="../lista_operadores.php" class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">

                    <form method="POST" action="../../controlador/servuser/guardar.php" class="formGuardarAsignaciones">

                        <!-- ID del usuario -->
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                        <?php if ($sqlServicios->num_rows > 0): ?>

                            <div class="row">

                                <?php while ($servicio = $sqlServicios->fetch_object()): ?>

                                    <div class="col-md-4 mb-3">
                                        <label class="w-100">
                                            <input type="checkbox" name="servicios[]" value="<?= $servicio->id_servicios ?>"
                                                class="d-none servicio-checkbox"
                                                <?= in_array($servicio->id_servicios, $asignados) ? 'checked' : '' ?>>

                                            <div class="card servicio-card h-100 shadow-sm
                                            <?= in_array($servicio->id_servicios, $asignados)
                                                ? 'bg-primary text-white border-primary'
                                                : 'border-secondary' ?>">
                                                <div class="card-body text-center py-3">
                                                    <i class="fa-solid fa-list-check mb-2 fs-4"></i>
                                                    <div class="fw-bold">
                                                        <?= htmlspecialchars($servicio->nombre_serv) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <!--
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check border rounded p-2">

                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="servicios[]"
                                                value="<= $servicio->id_servicios ?>"
                                                id="servicio<= $servicio->id_servicios ?>"
                                                <= in_array($servicio->id_servicios, $asignados) ? 'checked' : '' ?>>

                                            <label class="form-check-label"
                                                for="servicio<= $servicio->id_servicios ?>">
                                                <= htmlspecialchars($servicio->nombre_serv) ?>
                                            </label>

                                        </div>
                                    </div> -->

                                <?php endwhile; ?>

                            </div>

                        <?php else: ?>

                            <div class="alert alert-warning">
                                No existen servicios activos.
                            </div>

                        <?php endif; ?>

                        <hr>

                        <button type="submit" class="btn btn-primary btnGuardarAsignaciones">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Guardar Asignaciones
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="<?= BASE_URL ?>public/js/servuser.js"></script>

<?php include "../footer.php"; ?>