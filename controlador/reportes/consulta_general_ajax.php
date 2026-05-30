<?php

require_once __DIR__ . "/../../modelo/conexion.php";

$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';

$tipo = $_POST['tipo'] ?? 'hoy';

$fechaInicio = date('Y-m-d');
$fechaFin = date('Y-m-d');

switch ($tipo) {

    case 'hoy':
        $fechaInicio = date('Y-m-d');
        $fechaFin = date('Y-m-d');
        break;

    case 'semana':
        $fechaInicio = date('Y-m-d', strtotime('-6 days'));
        $fechaFin = date('Y-m-d');
        break;

    case 'mes':
        $fechaInicio = date('Y-m-01');
        $fechaFin = date('Y-m-d');
        break;

    case 'anio':
        $fechaInicio = date('Y-01-01');
        $fechaFin = date('Y-m-d');
        break;

    case 'personalizado':
        $fechaInicio = $_POST['inicio'];
        $fechaFin = $_POST['fin'];
        break;
}

$where = "WHERE t.fecha_tk BETWEEN '$fechaInicio' AND '$fechaFin'";

// FILTRO ESTADO
if (!empty($_POST['estado'])) {

    $estado = $conexion->real_escape_string($_POST['estado']);

    $where .= " AND t.estado_tk = '$estado'";
}

// FILTRO SERVICIO
if (!empty($_POST['servicio'])) {

    $servicio = intval($_POST['servicio']);

    $where .= " AND t.id_servicios = '$servicio'";
}

// FILTRO OPERADOR
if (!empty($_POST['operador'])) {

    $operador = intval($_POST['operador']);

    $where .= " AND t.id_usuario = '$operador'";
}

//###########################################################

if (!empty($search)) {

    $search = $conexion->real_escape_string($search);

    $where .= " AND (
        t.numero_tk LIKE '%$search%'
        OR s.nombre_serv LIKE '%$search%'
        OR u.nombre_user LIKE '%$search%'
        OR t.estado_tk LIKE '%$search%'
    )";
}

$totalQuery = $conexion->query("
    SELECT COUNT(*) as total
    FROM tickets t
    LEFT JOIN servicios s
    ON t.id_servicios = s.id_servicios
    LEFT JOIN usuarios u
    ON t.id_usuario = u.id_usuario
    $where
");

$totalRecords = $totalQuery->fetch_assoc()['total'];

$sql = "

SELECT

t.numero_tk,
s.nombre_serv,
t.estado_tk,
u.nombre_user,
t.fecha_tk,
t.creado_tk,
t.hora_cita,
t.hora_atencion,
t.hora_finalizado,

TIMESTAMPDIFF(
    MINUTE,
    t.creado_tk,
    t.hora_cita
) AS tiempo_espera,

TIMESTAMPDIFF(
    MINUTE,
    t.hora_atencion,
    t.hora_finalizado
) AS tiempo_atencion

FROM tickets t

LEFT JOIN servicios s
ON t.id_servicios = s.id_servicios

LEFT JOIN usuarios u
ON t.id_usuario = u.id_usuario

$where

ORDER BY 
    t.creado_tk DESC,
    t.id_tickets DESC,
    t.fecha_tk DESC

LIMIT $start, $length
";

$resultado = $conexion->query($sql);

$data = [];

$contador = $start + 1;

while ($row = $resultado->fetch_assoc()) {

    $estado = '';

    if ($row['estado_tk'] == 'FINALIZADO') {

        $estado = '<span class="badge bg-success">FINALIZADO</span>';
    } elseif ($row['estado_tk'] == 'PENDIENTE') {

        $estado = '<span class="badge bg-warning text-dark">PENDIENTE</span>';
    } elseif ($row['estado_tk'] == 'CANCELADO') {

        $estado = '<span class="badge bg-danger">CANCELADO</span>';
    } else {

        $estado = '<span class="badge bg-primary">' . $row['estado_tk'] . '</span>';
    }

    $data[] = [

        $contador++,
        $row['numero_tk'],
        $row['nombre_serv'],
        $estado,
        $row['nombre_user'],
        $row['fecha_tk'],
        $row['creado_tk'],
        $row['hora_cita'],
        $row['hora_atencion'],
        $row['hora_finalizado']
        //$row['tiempo_espera'] . ' min',
        //$row['tiempo_atencion'] . ' min'
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
]);
