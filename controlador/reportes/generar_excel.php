<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$fechaInicio = $_GET['inicio'] ?? '';
$fechaFin = $_GET['fin'] ?? '';

//PARA NOMBRE DE EXCEL
$inicio = date('d/m/Y', strtotime($fechaInicio));
$fin = date('d/m/Y', strtotime($fechaFin));

$estado = $_GET['estado'] ?? '';
$servicio = $_GET['servicio'] ?? '';
$operador = $_GET['operador'] ?? '';
$search = $_GET['search'] ?? '';

if ($search === 'undefined' || $search === null) {
    $search = '';
}

header("Content-Type: application/vnd.ms-excel");

//PARA NOMBRE DE EXCEL
$nombreArchivo = "consulta_general_" . $fechaInicio . "_a_" . $fechaFin . ".xls";
header("Content-Disposition: attachment; filename=$nombreArchivo");

header("Pragma: no-cache");
header("Expires: 0");

/* =========================
   WHERE BASE
========================= */
$where = "WHERE 1=1";

/* =========================
   FILTRO FECHAS (IMPORTANTE)
========================= */
if (!empty($fechaInicio) && !empty($fechaFin)) {
    $where .= " AND t.fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'";
}

/* =========================
   FILTRO ESTADO
========================= */
if (!empty($estado)) {
    $estado = $conexion->real_escape_string($estado);
    $where .= " AND t.estado_tk = '$estado'";
}

/* =========================
   FILTRO SERVICIO
========================= */
if (!empty($servicio)) {
    $servicio = intval($servicio);
    $where .= " AND t.id_servicios = '$servicio'";
}

/* =========================
   FILTRO OPERADOR
========================= */
if (!empty($operador)) {
    $operador = intval($operador);
    $where .= " AND t.id_usuario = '$operador'";
}

/* =========================
   BUSCADOR
========================= */
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
   SQL FINAL
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

/*
echo "<pre>";
var_dump($_GET);
exit;*/
?>

<h2>
    CONSULTA GENERAL
</h2>

<p>
    <strong>Rango:</strong>
    <?= $inicio ?> al <?= $fin ?>
</p>

<p>
    <strong>Fecha de generaciOn:</strong>
    <?= date('d/m/Y H:i:s') ?>
</p>

<br>

<table border="1">
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
        <?php
        $contador = 1;
        while ($row = $resultado->fetch_assoc()):
        ?>
            <tr>
                <td><?= $contador++ ?></td>
                <td><?= $row['numero_tk'] ?></td>
                <td><?= $row['nombre_serv'] ?></td>
                <td><?= $row['estado_tk'] ?></td>
                <td><?= $row['nombre_user'] ?></td>
                <td><?= $row['fecha_tk'] ?></td>
                <td><?= $row['creado_tk'] ?></td>
                <td><?= $row['hora_cita'] ?></td>
                <td><?= $row['hora_atencion'] ?></td>
                <td><?= $row['hora_finalizado'] ?></td>
            </tr>

        <?php endwhile; ?>
    </tbody>
</table>