<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$fechaInicio = $_GET['inicio'];
$fechaFin = $_GET['fin'];

$inicio = date('d/m/Y', strtotime($fechaInicio));
$fin = date('d/m/Y', strtotime($fechaFin));

header("Content-Type: application/vnd.ms-excel");

$nombreArchivo = "horas_pico_" . $fechaInicio . "_a_" . $fechaFin . ".xls";
header("Content-Disposition: attachment; filename=$nombreArchivo");

$sql = "

SELECT

HOUR(creado_tk) AS hora,
COUNT(*) AS total

FROM tickets

WHERE fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'

GROUP BY HOUR(creado_tk)

ORDER BY hora ASC

";

$resultado = $conexion->query($sql);

?>

<h2>
    REPORTE DE HORAS PICO
</h2>

<p>
    <strong>Rango:</strong>
    <?= $inicio ?> al <?= $fin ?>
</p>

<p>
    <strong>Fecha de generacion:</strong>
    <?= date('d/m/Y H:i:s') ?>
</p>

<br>

<table border="1">

    <tr>
        <th>Hora</th>
        <th>Tickets</th>
    </tr>

    <?php while($row = $resultado->fetch_assoc()): ?>

        <tr>

            <td><?= str_pad($row['hora'], 2, '0', STR_PAD_LEFT) ?>:00</td>

            <td><?= $row['total'] ?></td>

        </tr>

    <?php endwhile; ?>

</table>