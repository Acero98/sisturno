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
include_once __DIR__ . "/../../controlador/atencion/estadistica_operador.php";
include_once __DIR__ . "/../../controlador/atencion/estadistica_servicio.php";

//ESTADO DE LOS TICKETS Y BOTONES
$estado_ticket = strtoupper(trim($ticket['estado_tk'] ?? 'PENDIENTE'));

$titulo = '';
$mensaje = '';
$color = '';
$claseTicket = '';
$contador = 1;

switch ($estado_ticket) {

    case 'PENDIENTE':
        $titulo = 'LLAMAR A';
        $mensaje = 'En espera de atención';
        $color = '#e2c002';
        $claseTicket = 'ticket-parpadeo';
        break;

    case 'LLAMADO':
        $titulo = 'TICKET LLAMADO';
        $mensaje = 'Cliente en traslado a ventanilla';
        $color = '#0d6efd';
        $claseTicket = 'ticket-parpadeo';
        break;

    case 'EN_ATENCION':
        $titulo = 'ATENDIENDO';
        $mensaje = 'Atención en proceso';
        $color = '#198754';
        $claseTicket = 'ticket-atendiendo';
        break;
}
?>
<style>
    .ticket-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .ticket-numero {
        font-size: 5rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: 4px;
    }

    .ticket-parpadeo {
        animation: ticketPulse 1.5s infinite;
    }

    .ticket-atendiendo {
        text-shadow: 0 0 15px rgba(25, 135, 84, .35);
    }

    @keyframes ticketPulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.08);
            opacity: .85;
        }
    }
</style>

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
        =========================================================== -->
            <div class="card border-0 shadow-sm ticket-card mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <!-- Ticket -->
                        <div class="col-md-6 text-center border-end">
                            <div class="ticket-numero <?= $claseTicket ?>"
                                style="color: <?= $color ?>;">
                                <?= htmlspecialchars($ticket['numero_tk']) ?>
                            </div>
                        </div>
                        <!-- Información -->
                        <div class="col-md-6 text-center">
                            <div class="text-uppercase text-muted fw-bold mb-2"
                                style="letter-spacing:3px;">
                                <?= $titulo ?>
                            </div>
                            <div class="fw-bold fs-2 text-dark">
                                <?= htmlspecialchars($ticket['servicio']) ?>
                            </div>
                            <div class="text-secondary mt-1">
                                <?= $mensaje ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <button
                    class="btn btn-primary btn-lg w-100 py-3 fw-bold"
                    onclick="llamarTicket(this)"
                    data-ticket="<?= $ticket['numero_tk'] ?>">

                    LLAMAR A <?= $ticket['numero_tk'] ?>

                </button>
            </div>

        <?php elseif ($estado_ticket === 'LLAMADO'): ?>

            <!-- ==========================================================
            TICKET YA FUE LLAMADO
        =========================================================== -->
            <div class="card border-0 shadow-sm ticket-card mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <!-- Ticket -->
                        <div class="col-md-6 text-center border-end">
                            <div class="ticket-numero <?= $claseTicket ?>"
                                style="color: <?= $color ?>;">
                                <?= htmlspecialchars($ticket['numero_tk']) ?>
                            </div>
                        </div>
                        <!-- Información -->
                        <div class="col-md-6 text-center">
                            <div class="text-uppercase text-muted fw-bold mb-2"
                                style="letter-spacing:3px;">
                                <?= $titulo ?>
                            </div>
                            <div class="fw-bold fs-2 text-dark">
                                <?= htmlspecialchars($ticket['servicio']) ?>
                            </div>
                            <div class="text-secondary mt-1">
                                <?= $mensaje ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <button
                        class="btn btn-success btn-lg w-100 py-3 fw-bold"
                        onclick="comenzarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">

                        COMENZAR ATENCIÓN

                    </button>
                </div>

                <div class="col-md-6">
                    <button
                        class="btn btn-danger btn-lg w-100 py-3 fw-bold"
                        onclick="cancelarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">

                        CANCELAR

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
            <div class="card border-0 shadow-sm ticket-card mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <!-- Ticket -->
                        <div class="col-md-6 text-center border-end">
                            <div class="ticket-numero <?= $claseTicket ?>"
                                style="color: <?= $color ?>;">
                                <?= htmlspecialchars($ticket['numero_tk']) ?>
                            </div>
                        </div>
                        <!-- Información -->
                        <div class="col-md-6 text-center">
                            <div class="text-uppercase text-muted fw-bold mb-2"
                                style="letter-spacing:3px;">
                                <?= $titulo ?>
                            </div>
                            <div class="fw-bold fs-2 text-dark">
                                <?= htmlspecialchars($ticket['servicio']) ?>
                            </div>
                            <div class="text-secondary mt-1">
                                <?= $mensaje ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="row g-3">

                <div class="col-md-6">
                    <button
                        class="btn btn-primary btn-lg w-100 py-3 fw-bold"
                        onclick="finalizarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">

                        FINALIZAR ATENCIÓN

                    </button>
                </div>

                <div class="col-md-6">
                    <button
                        class="btn btn-danger btn-lg w-100 py-3 fw-bold"
                        onclick="cancelarAtencion(this)"
                        data-ticket="<?= $ticket['numero_tk'] ?>">

                        CANCELAR

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
        <div class="col-lg-6">
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
                                        <th>Servicio</th>
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
                                            </td>
                                            <td>
                                                <?php if (!empty($t['servicio'])): ?>
                                                    <?= $t['servicio'] ?>
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
        <div class="col-lg-6">
            <!-- Tarjetas -->
            <div class="row g-3">
                <!-- tarjetas aquí -->
                <!-- Atendidos -->
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Atendidos Hoy</small>
                                    <h2 class="fw-bold text-primary mb-0">
                                        <?= $estadisticas['total_tickets'] ?? 0 ?>
                                    </h2>
                                </div>
                                <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- En Espera -->
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">En Espera</small>
                                    <h2 class="fw-bold text-warning mb-0">
                                        <?= count($tickets) ?? 0 ?>
                                    </h2>
                                </div>
                                <i class="bi bi-hourglass-split text-warning fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Finalizados -->
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Finalizados</small>
                                    <h2 class="fw-bold text-success mb-0">
                                        <?= $estadisticas['finalizados'] ?? 0 ?>
                                    </h2>
                                </div>
                                <i class="bi bi-clipboard-check-fill text-info fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancelados -->
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Cancelados</small>
                                    <h2 class="fw-bold text-danger mb-0">
                                        <?= $estadisticas['cancelados'] ?? 0 ?>
                                    </h2>
                                </div>
                                <i class="bi bi-x-circle-fill text-secondary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiempo Atención -->
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Prom. Atención</small>
                                    <h2 class="fw-bold text-info mb-0">
                                        <?= round($promedio['promedio_atencion'] ?? 0) ?> min
                                    </h2>
                                </div>
                                <i class="bi bi-stopwatch-fill text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiempo Espera -->
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Prom. Espera</small>
                                    <h2 class="fw-bold text-info mb-0">
                                        <?= round($espera['promedio_espera'] ?? 0) ?> min
                                    </h2>
                                </div>
                                <i class="bi bi-clock-history text-danger fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla servicios -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-info text-dark fw-bold">
                    <i class="fas fa-chart-bar me-2"></i>
                    Atenciones por Servicio
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Servicio</th>
                                    <th width="100" class="text-center">
                                        Cantidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fila = $servicios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?=$contador?></td>
                                        <td>
                                            <?= htmlspecialchars($fila['nombre_serv']) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $fila['cantidad'] ?>
                                        </td>
                                    </tr>

                                    <?php
                                    $contador = $contador + 1;
                                    ?>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>