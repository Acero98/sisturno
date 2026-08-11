<div class="table-responsive table-turnos-wrap">
    <table class="table table-hover table-turnos align-middle">
        <thead class="table-light">
            <tr>
                <th>N°</th>
                <th>TICKET | SERVICIO</th>
                <th>VENTANILLA</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($turnos as $indice => $turno):
                $estado = $turno['estado_tk'];
                $claseFila = $estado === 'LLAMADO' ? 'fila-llamado' : ($estado === 'EN_ATENCION' ? 'table-success' : 'table-warning');
                $badge = $estado === 'LLAMADO' ? 'bg-primary' : ($estado === 'EN_ATENCION' ? 'bg-success' : 'bg-warning text-dark');
                $esActivo = in_array($estado, ['LLAMADO', 'EN_ATENCION'], true);
                $ventanilla = $esActivo && $turno['num_ventanilla'] !== null && $turno['num_ventanilla'] !== '' ? str_pad($turno['num_ventanilla'], 2, '0', STR_PAD_LEFT) : '--';
                $texto = $turno['numero_tk'];
                if ($estado === 'PENDIENTE') $texto .= ' | ' . strtoupper($turno['nombre_serv']);
                elseif ($ventanilla === '--') $texto .= ' - VENTANILLA SIN ASIGNAR';
            ?>
                <tr class="<?= $claseFila ?>">
                    <th class="text-center align-middle fw-bold numero-turno"><?= $indice + 1 ?></th>
                    <td class="text-center align-middle fw-bold ticket-turno"><?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-center align-middle fw-bold ventanilla-turno"><?= htmlspecialchars($ventanilla, ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-center align-middle">
                        <span class="badge <?= $badge ?> rounded-pill estado-turno">
                            <?= htmlspecialchars(str_replace('_', ' ', $estado), ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$turnos): ?><tr>
                    <td colspan="4" class="text-center py-5 text-muted fw-bold sin-turnos">NO HAY TURNOS ACTIVOS</td>
                </tr><?php endif; ?>
        </tbody>
    </table>
</div>