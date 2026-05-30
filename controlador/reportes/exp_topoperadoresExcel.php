<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$fechaInicio = $_GET['inicio'];
$fechaFin = $_GET['fin'];

$inicio = date('d-m-Y', strtotime($fechaInicio));
$fin = date('d-m-Y', strtotime($fechaFin));

$nombreArchivo = "top_operadores_{$inicio}_a_{$fin}.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo");

$sql = "

SELECT

u.nombre_user,

COUNT(t.id_tickets) as total_atenciones,

SUM(
    CASE
        WHEN t.estado_tk = 'FINALIZADO'
        THEN 1
        ELSE 0
    END
) as finalizados,

SUM(
    CASE
        WHEN t.estado_tk = 'CANCELADO'
        THEN 1
        ELSE 0
    END
) as cancelados,

ROUND(
    AVG(
        TIMESTAMPDIFF(
            MINUTE,
            t.hora_atencion,
            t.hora_finalizado
        )
    )
) as promedio_atencion

FROM tickets t

INNER JOIN usuarios u
ON t.id_usuario = u.id_usuario

WHERE t.fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'

GROUP BY u.nombre_user

ORDER BY total_atenciones DESC

";

/* =====================================
   EJECUTAR CONSULTA
===================================== */

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error SQL: " . $conexion->error);
}

?>

<h2>
    REPORTE TOP OPERADORES
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
    <th>OPERADOR</th>
    <th>TICKETS</th>
    <th>FINALIZADOS</th>
    <th>CANCELADOS</th>
    <th>PROMEDIO</th>
    <th>EFICIENCIA</th>

</tr>

<?php

$contador = 1;

while($row = $resultado->fetch_assoc()):

    $eficiencia = 0;

    if($row['total_atenciones'] > 0){

        $eficiencia = (
            $row['finalizados']
            /
            $row['total_atenciones']
        ) * 100;
    }

?>

<tr>

    <td><?= $contador++ ?></td>

    <td>
        <?= $row['nombre_user'] ?>
    </td>

    <td>
        <?= $row['total_atenciones'] ?>
    </td>

    <td>
        <?= $row['finalizados'] ?>
    </td>

    <td>
        <?= $row['cancelados'] ?>
    </td>

    <td>
        <?= round($row['promedio_atencion']) ?> min
    </td>

    <td>
        <?= round($eficiencia, 1) ?>%
    </td>

</tr>

<?php endwhile; ?>

</table>