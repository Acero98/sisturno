<?php

require_once __DIR__ . "/../../modelo/conexion.php";
require_once __DIR__ . "/../../dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;

/* =========================
   FILTROS
========================= */

$fechaInicio = $_GET['inicio'] ?? '';
$fechaFin = $_GET['fin'] ?? '';

//PARA NOMBRE DE PDF
$inicio = date('d/m/Y', strtotime($fechaInicio));
$fin = date('d/m/Y', strtotime($fechaFin));

$estado = $_GET['estado'] ?? '';
$servicio = $_GET['servicio'] ?? '';
$operador = $_GET['operador'] ?? '';
$search = $_GET['search'] ?? '';

if ($search === 'undefined' || $search === null) {
    $search = '';
}

/* =========================
   WHERE
========================= */

$where = "WHERE 1=1";

if (!empty($fechaInicio) && !empty($fechaFin)) {
    $where .= " AND t.fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'";
}

if (!empty($estado)) {
    $estado = $conexion->real_escape_string($estado);
    $where .= " AND t.estado_tk = '$estado'";
}

if (!empty($servicio)) {
    $servicio = intval($servicio);
    $where .= " AND t.id_servicios = '$servicio'";
}

if (!empty($operador)) {
    $operador = intval($operador);
    $where .= " AND t.id_usuario = '$operador'";
}

if (!empty($search)) {

    $search = $conexion->real_escape_string($search);

    $where .= " AND (
        t.numero_tk LIKE '%$search%'
        OR s.nombre_serv LIKE '%$search%'
        OR u.nombre_user LIKE '%$search%'
        OR t.estado_tk LIKE '%$search%'
    )";
}

/* =========================
   SQL
========================= */

$sql = "SELECT
    t.numero_tk,
    s.nombre_serv,
    t.estado_tk,
    u.nombre_user,
    t.fecha_tk,
    t.creado_tk,
    t.hora_cita,
    t.hora_atencion,
    t.hora_finalizado
FROM tickets t
LEFT JOIN servicios s ON t.id_servicios = s.id_servicios
LEFT JOIN usuarios u ON t.id_usuario = u.id_usuario
$where
ORDER BY t.fecha_tk DESC";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error SQL: " . $conexion->error);
}

/* =========================
   HTML PDF
========================= */

$html = '

<style>

body{
    font-family: Arial, sans-serif;
    font-size: 11px;
}

h2{
    text-align: center;
    margin-bottom: 15px;
}

table{
    width: 100%;
    border-collapse: collapse;
}

th{
    background: #343a40;
    color: white;
    padding: 6px;
    border: 1px solid #ddd;
}

td{
    padding: 5px;
    border: 1px solid #ddd;
}

</style>

<h2>Reporte General de Tickets</h2>

<p>
    <strong>Rango:</strong>
    '.$inicio.' al '.$fin.'
</p>

<p>
    <strong>Fecha de generación:</strong>
    '.date('d/m/Y H:i:s').'
</p>

<br>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>TICKET</th>
            <th>SERVICIO</th>
            <th>ESTADO</th>
            <th>OPERADOR</th>
            <th>FECHA</th>
            <th>CREADO</th>
            <th>LLAMADO</th>
            <th>ATENCION</th>
            <th>FINALIZADO</th>
        </tr>
    </thead>

    <tbody>
';

$contador = 1;

while ($row = $resultado->fetch_assoc()) {

    $html .= '
        <tr>
            <td>'.$contador++.'</td>
            <td>'.$row['numero_tk'].'</td>
            <td>'.$row['nombre_serv'].'</td>
            <td>'.$row['estado_tk'].'</td>
            <td>'.$row['nombre_user'].'</td>
            <td>'.$row['fecha_tk'].'</td>
            <td>'.$row['creado_tk'].'</td>
            <td>'.$row['hora_cita'].'</td>
            <td>'.$row['hora_atencion'].'</td>
            <td>'.$row['hora_finalizado'].'</td>
        </tr>
    ';
}

$html .= '
    </tbody>
</table>
';

/* =========================
   GENERAR PDF
========================= */

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$nombreArchivo = "consulta_general_" . $fechaInicio . "_a_" . $fechaFin . ".pdf";

$dompdf->stream(
    $nombreArchivo,
    ["Attachment" => true]
);