<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$fechaInicio = $_GET['inicio'];
$fechaFin = $_GET['fin'];

$inicio = date('d-m-Y', strtotime($fechaInicio));
$fin = date('d-m-Y', strtotime($fechaFin));

$nombreArchivo = "tendencia_tickets_{$inicio}_a_{$fin}.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo");

$sql = "

SELECT

fecha_tk,
COUNT(*) as total

FROM tickets

WHERE fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'

GROUP BY fecha_tk

ORDER BY fecha_tk ASC

";

$resultado = $conexion->query($sql);

?>

<h2>
    REPORTE DE TENDENCIA DE TICKETS
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
        <th>FECHA</th>
        <th>TICKETS</th>
        <th>VARIACION</th>

    </tr>

    <?php

    $contador = 1;

    $anterior = 0;

    while($row = $resultado->fetch_assoc()):

        $actual = $row['total'];

        if($anterior > 0){

            $variacion = (($actual - $anterior) / $anterior) * 100;

        }else{

            $variacion = 0;
        }

    ?>

        <tr>

            <td><?= $contador++ ?></td>

            <td>
                <?= date('d/m/Y', strtotime($row['fecha_tk'])) ?>
            </td>

            <td>
                <?= $actual ?>
            </td>

            <td>

                <?= round($variacion, 1) ?>%

            </td>

        </tr>

    <?php

        $anterior = $actual;

    endwhile;

    ?>

</table>