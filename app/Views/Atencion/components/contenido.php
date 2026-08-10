<?php
$estadoTicket = strtoupper(trim($ticket['estado_tk'] ?? 'PENDIENTE'));
$configuracion = [
    'PENDIENTE' => ['LLAMAR A', 'En espera de atención', '#e2c002', 'ticket-parpadeo'],
    'LLAMADO' => ['TICKET LLAMADO', 'Cliente en traslado a ventanilla', '#0d6efd', 'ticket-parpadeo'],
    'EN_ATENCION' => ['ATENDIENDO', 'Atención en proceso', '#198754', 'ticket-atendiendo'],
];
[$titulo, $mensaje, $color, $claseTicket] = $configuracion[$estadoTicket] ?? ['', '', '#6c757d', ''];
?>
<style>
    .ticket-card {
        border-radius: 18px;
        overflow: hidden
    }

    .ticket-numero {
        font-size: 5rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: 4px
    }

    .ticket-parpadeo {
        animation: ticketPulse 1.5s infinite
    }

    .ticket-atendiendo {
        text-shadow: 0 0 15px rgba(25, 135, 84, .35)
    }

    @keyframes ticketPulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 1
        }

        50% {
            transform: scale(1.08);
            opacity: .85
        }
    }
</style>
<div class="container-fluid py-4 atencion-mvc estado-<?= strtolower($estadoTicket) ?>">
    <h4 class="fw-bold text-center fw-semibold">ATENCIÓN AL CLIENTE - VENTANILLA <?= htmlspecialchars($ventanilla['num_ventanilla'] ?? 'SIN ASIGNAR', ENT_QUOTES, 'UTF-8') ?></h4>
    <?php if ($ticket): ?>
        <div class="card border-0 shadow-sm ticket-card mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center border-end">
                        <div class="ticket-numero <?= $claseTicket ?>"><?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="text-uppercase text-muted fw-bold mb-2" style="letter-spacing:3px"><?= $titulo ?></div>
                        <div class="fw-bold fs-2 text-dark"><?= htmlspecialchars($ticket['servicio'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="text-secondary mt-1"><?= $mensaje ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($estadoTicket === 'PENDIENTE'): ?>
            <div class="text-center mb-4">
                <button class="btn btn-primary btn-lg w-100 py-3 fw-bold" onclick="llamarTicket(this)" data-ticket="<?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?>">
                    <i class="fa-solid fa-bullhorn me-2" aria-hidden="true"></i>
                    LLAMAR A
                    <?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?>
                </button>
            </div>
        <?php elseif ($estadoTicket === 'LLAMADO'): ?><div class="row g-3">
                <div class="col-md-6">
                    <button class="btn btn-success btn-lg w-100 py-3 fw-bold" onclick="comenzarAtencion(this)" data-ticket="<?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?>">
                        <i class="fa-solid fa-play me-2" aria-hidden="true"></i>
                        COMENZAR ATENCIÓN
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-danger btn-lg w-100 py-3 fw-bold" onclick="cancelarAtencion(this)" data-ticket="<?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?>">
                        <i class="fa-solid fa-ban me-2" aria-hidden="true"></i>
                        CANCELAR
                    </button>
                </div>
            </div>
        <?php elseif ($estadoTicket === 'EN_ATENCION'): ?><div class="row g-3">
                <div class="col-md-6">
                    <button class="btn btn-primary btn-lg w-100 py-3 fw-bold" onclick="finalizarAtencion(this)" data-ticket="<?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?>">
                        <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i>
                        FINALIZAR ATENCIÓN
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-danger btn-lg w-100 py-3 fw-bold" onclick="cancelarAtencion(this)" data-ticket="<?= htmlspecialchars($ticket['numero_tk'], ENT_QUOTES, 'UTF-8') ?>">
                        <i class="fa-solid fa-ban me-2" aria-hidden="true"></i>
                        CANCELAR
                    </button>
                </div>
            </div><?php endif; ?>
    <?php else: ?><div class="alert alert-secondary text-center fw-semibold">NO HAY ATENCIONES PENDIENTES</div><?php endif; ?>
    <div class="row g-4 mt-2">
        <div class="col-lg-6">
            <div class="card card-custom shadow-sm border-0 h-100">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list-ol me-2">
                        </i>Próximos 5 Tickets</span>
                    <?php if ($tickets): ?>
                        <span class="badge bg-light text-primary fw-bold px-3 py-2">
                            <?= count($tickets) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0"><?php if (!$tickets): ?><div class="text-center py-5 px-4"><i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                            <h5 class="fw-bold text-secondary">No hay tickets pendientes</h5>
                            <p class="text-muted mb-0">Cuando existan nuevos turnos, aparecerán en esta lista.</p>
                        </div><?php else: ?><div class="table-responsive">
                            <table class="table table-hover align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Estado</th>
                                        <th>Ticket</th>
                                        <th>Servicio</th>
                                    </tr>
                                </thead>
                                <tbody><?php foreach (array_slice($tickets, 0, 5) as $ticketPendiente): ?><tr>
                                            <td class="fw-bold text-muted"><?= $ticketPendiente['numero'] ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-semibold">
                                                    <?= htmlspecialchars(str_replace('_', ' ', $ticketPendiente['estado']), ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary fs-5"><?= htmlspecialchars($ticketPendiente['ticket'], ENT_QUOTES, 'UTF-8') ?></div>
                                            </td>
                                            <td><?= htmlspecialchars($ticketPendiente['servicio'], ENT_QUOTES, 'UTF-8') ?></td>
                                        </tr><?php endforeach; ?></tbody>
                            </table>
                        </div><?php endif; ?></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row g-3">
                <?php
                $tarjetas = [
                    ['Atendidos Hoy', $estadisticas['total_tickets'] ?? 0, 'text-primary'],
                    ['En Espera', count($tickets), 'text-warning'],
                    ['Finalizados', $estadisticas['finalizados'] ?? 0, 'text-success'],
                    ['Cancelados', $estadisticas['cancelados'] ?? 0, 'text-danger'],
                    ['Prom. Atención', round($estadisticas['promedio_atencion'] ?? 0) . ' min', 'text-info'],
                    ['Prom. Espera', round($estadisticas['promedio_espera'] ?? 0) . ' min', 'text-info']
                ];
                foreach ($tarjetas as [$etiqueta, $valor, $clase]): ?><div class="col-6 col-md-4">
                        <div class="card estadistica-card shadow-sm border-0 h-100">
                            <div class="card-body"><small class="estadistica-etiqueta text-muted"><?= $etiqueta ?></small>
                                <h4 class="estadistica-valor fw-bold <?= $clase ?> mb-0 text-nowrap"><?= $valor ?></h4>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-info text-dark fw-bold"><i class="fas fa-chart-bar me-2"></i>Atenciones por Servicio</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Servicio</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody><?php foreach ($servicios as $indice => $servicio): ?><tr>
                                        <td><?= $indice + 1 ?></td>
                                        <td><?= htmlspecialchars($servicio['nombre_serv'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-center"><?= (int)$servicio['cantidad'] ?></td>
                                    </tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
