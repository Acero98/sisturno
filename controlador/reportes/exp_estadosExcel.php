<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$fechaInicio = $_GET['inicio'];
$fechaFin = $_GET['fin'];

$inicio = date('d-m-Y', strtotime($fechaInicio));
$fin = date('d-m-Y', strtotime($fechaFin));

$nombreArchivo = "estados_tickets_{$inicio}_a_{$fin}.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo");

/* =========================
   TOTAL GENERAL
========================= */

$totalQuery = $conexion->query("

SELECT COUNT(*) as total

FROM tickets

WHERE fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'

");

$totalTickets = $totalQuery->fetch_assoc()['total'];

/* =========================
   CONSULTA PRINCIPAL

   Saturación

Si pendientes > 30%

mostrar:

ALTA CARGA OPERATIVA

Si cancelados > 15%

mostrar:

ALTO NIVEL DE CANCELACIONES

Si finalizados > 80%

mostrar:

OPERACIÓN ESTABLE
========================= */

$sql = "

SELECT

estado_tk,
COUNT(*) as total

FROM tickets

WHERE fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'

GROUP BY estado_tk

ORDER BY total DESC

";

$resultado = $conexion->query($sql);

?>

<h2>
    REPORTE DE ESTADOS DE TICKETS
</h2>

<p>
    <strong>Rango:</strong>
    <?= $inicio ?> al <?= $fin ?>
</p>

<p>
    <strong>Generado:</strong>
    <?= date('d/m/Y H:i:s') ?>
</p>

<p>
    <strong>Total Tickets:</strong>
    <?= number_format($totalTickets) ?>
</p>

<br>

<table border="1">

<tr>

    <th>#</th>
    <th>ESTADO</th>
    <th>TOTAL</th>
    <th>PORCENTAJE</th>
    <th>INDICADOR</th>

</tr>

<?php

$contador = 1;

while($row = $resultado->fetch_assoc()):

    /* =========================
       PORCENTAJE
    ========================= */

    $porcentaje = 0;

    if($totalTickets > 0){

        $porcentaje = (
            $row['total']
            /
            $totalTickets
        ) * 100;
    }

    /* =========================
       INDICADORES
    ========================= */

    $indicador = "NORMAL";

    if(
        $row['estado_tk'] == 'PENDIENTE'
        &&
        $porcentaje >= 30
    ){

        $indicador = 'SATURACION OPERATIVA';
    }

    elseif(
        $row['estado_tk'] == 'CANCELADO'
        &&
        $porcentaje >= 15
    ){

        $indicador = 'ALTA CANCELACION';
    }

    elseif(
        $row['estado_tk'] == 'FINALIZADO'
        &&
        $porcentaje >= 80
    ){

        $indicador = 'RENDIMIENTO OPTIMO';
    }

?>

<tr>

    <td><?= $contador++ ?></td>

    <td>
        <?= $row['estado_tk'] ?>
    </td>

    <td>
        <?= number_format($row['total']) ?>
    </td>

    <td>
        <?= round($porcentaje, 1) ?>%
    </td>

    <td>
        <?= $indicador ?>
    </td>

</tr>

<?php endwhile; ?>

</table>