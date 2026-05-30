<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$fechaInicio = $_GET['inicio'];
$fechaFin = $_GET['fin'];

$inicio = date('d-m-Y', strtotime($fechaInicio));
$fin = date('d-m-Y', strtotime($fechaFin));

$nombreArchivo = "servicios_mas_solicitados_{$inicio}_a_{$fin}.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo");

$sql = "

SELECT

s.nombre_serv,

COUNT(*) as total,

(
    COUNT(*) * 100 /
    (
        SELECT COUNT(*)
        FROM tickets
        WHERE fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'
    )
) as porcentaje

FROM tickets t

INNER JOIN servicios s
ON t.id_servicios = s.id_servicios

WHERE t.fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'

GROUP BY s.nombre_serv

ORDER BY total DESC

";

$resultado = $conexion->query($sql);

?>

<h2>
    REPORTE DE SERVICIOS MAS SOLICITADOS
</h2>

<p>
    <strong>Rango:</strong>
    <?= $inicio ?> al <?= $fin ?>
</p>

<p>
    <strong>Generado:</strong>
    <?= date('d/m/Y H:i:s') ?>
</p>

<br>

<table border="1">

    <tr>
        <th>#</th>
        <th>SERVICIO</th>
        <th>TICKETS</th>
        <th>%</th>
    </tr>

    <?php

    $contador = 1;

    while ($row = $resultado->fetch_assoc()):

    ?>

        <tr>

            <td><?= $contador++ ?></td>
            <td><?= $row['nombre_serv'] ?></td>
            <td><?= $row['total'] ?></td>
            <td>
                <?= round($row['porcentaje'], 1) ?>%
            </td>

        </tr>

    <?php endwhile; ?>

</table>