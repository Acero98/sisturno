<?php
include "../modelo/conexion.php";
?>

<table class="table table-hover table-turnos align-middle">
    <thead class="table-light">
        <tr>
            <th style="width: 70px;">N°</th>
            <th style="width: 220px;">TICKET | SERVICIO</th>
            <!-- <th>SERVICIO</th> -->
            <th style="width: 170px;">ESTADO</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = $conexion->query("SELECT
                                    t.numero_tk,
                                    t.estado_tk,
                                    s.codigo_serv,
                                    s.nombre_serv,
                                    u.num_ventanilla
                                FROM tickets t
                                INNER JOIN servicios s
                                    ON t.id_servicios = s.id_servicios
                                LEFT JOIN usuarios u
                                    ON t.id_usuario = u.id_usuario
                                WHERE t.estado_tk IN ('PENDIENTE', 'LLAMADO', 'EN_ATENCION')
                                AND t.fecha_tk = CURRENT_DATE
                                ORDER BY
                                    CASE
                                        WHEN t.estado_tk = 'LLAMADO' THEN 1
                                        WHEN t.estado_tk = 'EN_ATENCION' THEN 2
                                        WHEN t.estado_tk = 'PENDIENTE' THEN 3
                                        ELSE 4
                                    END,
                                    t.creado_tk ASC
                                LIMIT 8
                            ");

        $contador = 1;

        while ($datos = $sql->fetch_object()) {

            /*----------------------------------------------------------
    | Color según el estado
    ----------------------------------------------------------*/
            $claseFila = 'table-light';
            $badge = 'bg-secondary';

            if ($datos->estado_tk == 'PENDIENTE') {
                $claseFila = 'table-warning';
                $badge = 'bg-warning text-dark';
            } elseif ($datos->estado_tk == 'LLAMADO') {
                //$claseFila = 'table-primary';
                $claseFila = 'fila-llamado'; // cambiamos esto
                $badge = 'bg-primary';
            } elseif ($datos->estado_tk == 'EN_ATENCION') {
                $claseFila = 'table-success';
                $badge = 'bg-success';
            }

            /*----------------------------------------------------------
    | Texto principal a mostrar
    |
    | PENDIENTE:
    |   DUP-009 - DUPLICADO DE RECIBO
    |
    | LLAMADO / EN_ATENCION:
    |   DUP-009 - IR A VENTANILLA 07
    ----------------------------------------------------------*/
            $textoTicket = $datos->numero_tk;

            if ($datos->estado_tk == 'PENDIENTE') {

                // Mostrar nombre del servicio
                $textoTicket .= ' | ' . strtoupper($datos->nombre_serv);
            } elseif (
                in_array($datos->estado_tk, ['LLAMADO', 'EN_ATENCION']) &&
                !empty($datos->num_ventanilla)
            ) {

                // Mostrar ventanilla asignada
                $textoTicket .= ' - IR A VENTANILLA ' .
                    str_pad($datos->num_ventanilla, 2, '0', STR_PAD_LEFT);
            }
        ?>
            <tr class="<?= $claseFila ?>">

                <!-- NÚMERO -->
                <th class="text-center align-middle fw-bold"
                    style="font-size: 2.2rem; width: 90px;">
                    <?= $contador ?>
                </th>

                <!-- TICKET + MENSAJE -->
                <td class="text-center align-middle fw-bold"
                    style="font-size: 2.3rem; letter-spacing: 1px;">
                    <?= $textoTicket ?>
                </td>

                <!-- ESTADO -->
                <td class="text-center align-middle"
                    style="width: 220px;">
                    <span class="badge <?= $badge ?> rounded-pill px-4 py-3"
                        style="font-size: 1.4rem;">
                        <?= str_replace('_', ' ', $datos->estado_tk) ?>
                    </span>
                </td>
            </tr>
        <?php
            $contador++;
        }
        ?>

        <?php if ($contador === 1): ?>
            <tr>
                <td colspan="3"
                    class="text-center py-5 text-muted fw-bold"
                    style="font-size: 2.5rem;">
                    NO HAY TURNOS ACTIVOS
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>