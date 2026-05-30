<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../modelo/conexion.php";

require_once __DIR__ . "/../../control/auth.php";
require_once __DIR__ . "/../../control/permisos.php";

permitirSolo(["Super Admin", "Admin", "Operador"]);

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT num_ventanilla
        FROM usuarios u
        WHERE u.id_usuario = $id_usuario
        LIMIT 1
        ";

$result = $conexion->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$ventanilla = $result->fetch_assoc();

include_once __DIR__ . "/../../controlador/atencion/obtener_ticket.php";
?>

<div class="container-fluid py-4">

    <!-- TÍTULO -->
    <h4 class="fw-bold text-center fw-semibold">
        ATENCIÓN AL CLIENTE -
        VENTANILLA <?= $ventanilla['num_ventanilla'] ?? 'SIN ASIGNAR' ?>
    </h4>

    <!-- ALERTA -->
    <?php if ($ticket): ?>

        <?php
        /*
    |--------------------------------------------------------------------------
    | Dependiendo del estado del ticket mostramos una interfaz diferente
    |--------------------------------------------------------------------------
    | PENDIENTE    -> Botón "Llamar Ticket"
    | LLAMADO      -> Botones "Comenzar Atención" y "Cancelar Atención"
    | EN ATENCION  -> Mensaje informativo
    |--------------------------------------------------------------------------
    */
        $estado_ticket = strtoupper(trim($ticket['estado_tk'] ?? 'PENDIENTE'));
        ?>

        <?php if ($estado_ticket === 'PENDIENTE'): ?>

            <!-- ==========================================================
             TICKET AÚN NO HA SIDO LLAMADO
        =========================================================== 
            <div class="alert alert-info text-center fw-bold fs-4">
                LLAMAR A <= $ticket['numero_tk'] ?>
            </div>-->

            <div class="text-center mb-4">
                <button class="btn btn-primary btn-lg w-100 py-3 fw-bold"
                    onclick="llamarTicket(this)"
                    data-ticket="<?= $ticket['numero_tk'] ?>">
                    LLAMAR A <?= $ticket['numero_tk'] ?>
                </button>
            </div>

        <?php elseif ($estado_ticket === 'LLAMADO'): ?>

            <!-- ==========================================================
             TICKET YA FUE LLAMADO
        =========================================================== -->
            <div class="alert alert-light text-center fw-bold fs-3 text-secondary">
                HAS LLAMADO A <?= $ticket['numero_tk'] ?>
            </div>

            <div class="row g-3 mb-4">
                <!-- BOTÓN COMENZAR ATENCIÓN -->
                <div class="col-md-6">
                    <button class="btn btn-success btn-lg w-100 py-3 fw-bold" onclick="comenzarAtencion(this)" data-ticket="<?= $ticket['numero_tk'] ?>">
                        COMENZAR ATENCIÓN
                    </button>
                </div>

                <!-- BOTÓN CANCELAR ATENCIÓN -->
                <div class="col-md-6">
                    <button class="btn btn-danger btn-lg w-100 py-3 fw-bold"
                        onclick="cancelarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">
                        CANCELAR ATENCIÓN
                    </button>
                </div>
            </div>

            <!-- Mensaje para el operador 
            <div class="alert alert-success text-center">
                El cliente debe dirigirse a la
                <strong>VENTANILLA <= $ventanilla['num_ventanilla'] ?? 'SIN ASIGNAR' ?></strong>
            </div>-->

        <?php elseif ($estado_ticket === 'EN_ATENCION'): ?>

            <!-- ==========================================================
         TICKET EN PROCESO DE ATENCIÓN
    =========================================================== -->
            <div class="alert alert-light text-center fw-bold fs-3 text-secondary">
                ATENDIENDO <?= $ticket['numero_tk'] ?>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="row g-3 mb-4">
                <!-- FINALIZAR ATENCIÓN -->
                <div class="col-md-6">
                    <button class="btn btn-primary btn-lg w-100 py-3 fw-bold"
                        onclick="finalizarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">
                        FINALIZAR ATENCIÓN
                    </button>
                </div>

                <!-- CANCELAR ATENCIÓN -->
                <div class="col-md-6">
                    <button class="btn btn-danger btn-lg w-100 py-3 fw-bold"
                        onclick="cancelarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">
                        CANCELAR ATENCIÓN
                    </button>
                </div>
            </div>

        <?php else: ?>

            <!-- Estado no contemplado -->
            <div class="alert alert-warning text-center">
                Estado actual: <?= $estado_ticket ?>
            </div>

        <?php endif; ?>

    <?php else: ?>

        <!-- ==========================================================
         NO EXISTEN TICKETS PENDIENTES
    =========================================================== -->
        <div class="alert alert-secondary text-center fw-semibold">
            NO HAY ATENCIONES PENDIENTES
        </div>

    <?php endif; ?>

    <div class="row g-4 mt-2">

        <!-- COLUMNA IZQUIERDA -->
        <div class="col-lg-5">
            <div class="card card-custom shadow-sm border-0 h-100">

                <!-- ENCABEZADO -->
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-list-ol me-2"></i>
                        Próximos 5 Tickets
                    </span>

                    <?php if (!empty($tickets)): ?>
                        <span class="badge bg-light text-primary fw-bold px-3 py-2">
                            <?= count($tickets) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="card-body p-0">

                    <?php if (empty($tickets)): ?>

                        <!-- ESTADO VACÍO -->
                        <div class="text-center py-5 px-4">
                            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>

                            <h5 class="fw-bold text-secondary">
                                No hay tickets pendientes
                            </h5>

                            <p class="text-muted mb-0">
                                Cuando existan nuevos turnos, aparecerán en esta lista.
                            </p>
                        </div>

                    <?php else: ?>

                        <?php
                        // Obtener solamente los primeros 10 tickets
                        $primeros10 = array_slice($tickets, 0, 5);
                        ?>

                        <!-- TABLA -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 130px;">Estado</th>
                                        <th>Ticket</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($primeros10 as $t): ?>

                                        <?php
                                        // Definir color del estado
                                        $badgeClass = 'bg-secondary';

                                        if ($t['estado'] === 'PENDIENTE') {
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif ($t['estado'] === 'LLAMADO') {
                                            $badgeClass = 'bg-primary';
                                        } elseif ($t['estado'] === 'EN_ATENCION') {
                                            $badgeClass = 'bg-success';
                                        }
                                        ?>

                                        <tr>
                                            <!-- NÚMERO -->
                                            <td class="fw-bold text-muted">
                                                <?= $t['numero'] ?>
                                            </td>

                                            <!-- ESTADO -->
                                            <td>
                                                <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-semibold">
                                                    <?= str_replace('_', ' ', $t['estado']) ?>
                                                </span>
                                            </td>

                                            <!-- TICKET -->
                                            <td>
                                                <div class="fw-bold text-primary fs-5">
                                                    <?= $t['ticket'] ?>
                                                </div>

                                                <?php if (!empty($t['servicio'])): ?>
                                                    <small class="text-muted">
                                                        <?= $t['servicio'] ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>


                        <!-- PIE INFORMATIVO -->
                        <?php if (count($tickets) > 10): ?>
                            <div class="text-center py-3 border-top bg-light">
                                <small class="text-muted">
                                    Mostrando los primeros
                                    <strong>05</strong>
                                    de
                                    <strong><?= count($tickets) ?></strong>
                                    tickets pendientes.
                                </small>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>


            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="col-lg-7">
            <div class="card card-custom shadow-sm text-center">
                <div class="card-body empty-state py-5">

                    <!-- Puedes reemplazar por tu imagen -->
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076505.png" class="mb-4"
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