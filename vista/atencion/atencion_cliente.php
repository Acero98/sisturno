<?php

session_start();
require "../../modelo/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$sql = "
    SELECT v.numero_vent
    FROM usuarios u
    INNER JOIN ventanillas v 
        ON u.id_ventanilla = v.id_ventanilla
    WHERE u.id_usuario = $id_usuario
    LIMIT 1
";

$result = $conexion->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$ventanilla = $result->fetch_assoc();

include "../../controlador/atencion/obtener_ticket.php";
include "../header.php";

?>

<style>
    body {
        background-color: #f4f6f9;
    }

    .card-custom {
        border-radius: 12px;
    }

    .empty-state img {
        max-width: 250px;
    }
</style>

<div class="container-fluid py-4">

    <!-- TÍTULO -->
    <h4 class="fw-bold text-center fw-semibold">
        ATENCIÓN AL CLIENTE - 
        VENTANILLA <?= $ventanilla['numero_vent'] ?? 'SIN ASIGNAR' ?>
    </h4>

    <!-- ALERTA -->
    <?php if ($ticket): ?>

    <div class="alert alert-info text-center fw-bold">
        Ticket pendiente: <?= $ticket['numero_tk'] ?>
        <br>
        <small>ID interno: <?= $ticket['id_tickets'] ?></small>
    </div>

    <div class="text-center">
        <button class="btn btn-primary btn-lg" onclick="llamarTicket()">
            Llamar Tickets
        </button>
    </div>

    <?php else: ?>

    <div class="alert alert-secondary text-center fw-semibold">
        NO HAY ATENCIONES PENDIENTES
    </div>

    <?php endif; ?>

    <div class="row g-4 mt-2">

        <!-- COLUMNA IZQUIERDA -->
        <div class="col-lg-5">
            <div class="card card-custom shadow-sm">
                <div class="card-header card-header-custom">
                    Tickets en Atención
                </div>
                <div class="card-body">

                    <?php if(empty($tickets)): ?>
                        <p class="text-center text-muted my-5">
                            No hay atenciones pendientes
                        </p>
                    <?php else: ?>
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>Estado</th>
                                    <th>Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($tickets as $t): ?>
                                    <tr>
                                        <td><?= $t['numero'] ?></td>
                                        <td><?= $t['estado'] ?></td>
                                        <td><?= $t['ticket'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="col-lg-7">
            <div class="card card-custom shadow-sm text-center">
                <div class="card-body empty-state py-5">

                    <!-- Puedes reemplazar por tu imagen -->
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076505.png" 
                         class="mb-4" 
                         alt="Esperando atención">

                    <h5 class="fw-bold">ESPERANDO POR UNA ATENCIÓN</h5>
                    <p class="text-muted">
                        Llama a un usuario para iniciar con una operación
                    </p>

                </div>
            </div>
        </div>

    </div>

</div>

<script src="<?= BASE_URL ?>public/js/atencionCliente.js"></script>

<?php

include "../footer.php";

?>