<?php
$tipo = $_GET['tipo'] ?? 'hoy';

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
        $fechaInicio = $_GET['inicio'];
        $fechaFin = $_GET['fin'];
        break;
}

function rango($campo, $inicio, $fin)
{
    return "$campo BETWEEN '$inicio' AND '$fin'";
}

// Total de tickets generados HOY
$total = $conexion->query(
    "SELECT COUNT(*) AS total 
    FROM tickets 
    WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets finalizados HOY
$atendidos = $conexion->query(
    "SELECT COUNT(*) AS total 
                                FROM tickets 
                                WHERE estado_tk = 'FINALIZADO' 
                                AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets pendientes HOY
$pendientes = $conexion->query(
    "SELECT COUNT(*) AS total 
                                FROM tickets 
                                WHERE estado_tk = 'PENDIENTE' 
                                AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// =====================================================
// TIEMPO PROMEDIO DE ESPERA
// =====================================================

$sqlTiempoEspera = "SELECT 
                        AVG(TIMESTAMPDIFF(MINUTE, creado_tk, hora_cita)) AS promedio_espera
                    FROM tickets
                    WHERE hora_cita IS NOT NULL
AND creado_tk IS NOT NULL
AND " . rango('fecha_tk', $fechaInicio, $fechaFin);

$resultTiempoEspera = $conexion->query($sqlTiempoEspera);

$promedioEspera = 0;

if ($resultTiempoEspera && $row = $resultTiempoEspera->fetch_assoc()) {

    $promedioEspera = $row['promedio_espera'] !== null
        ? round($row['promedio_espera'])
        : 0;
}



//TABLA GENERAL DE TICKETS

$sqlTablaGeneral = "SELECT

t.numero_tk,
s.nombre_serv,
t.estado_tk,
u.nombre_user,
t.fecha_tk,

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

WHERE " . rango('t.fecha_tk', $fechaInicio, $fechaFin) . "

ORDER BY t.fecha_tk DESC";

$tablaGeneral = $conexion->query($sqlTablaGeneral);

//TABLA DE SERVICIOS MÁS SOLICITADOS

$sqlServiciosTabla = "SELECT

s.nombre_serv,
COUNT(*) AS total,

ROUND(
    (
        COUNT(*) * 100.0 /
        (SELECT COUNT(*) 
         FROM tickets
         WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin) . ")
    ),
2) AS porcentaje

FROM tickets t

INNER JOIN servicios s
ON t.id_servicios = s.id_servicios

WHERE " . rango('t.fecha_tk', $fechaInicio, $fechaFin) . "

GROUP BY s.id_servicios, s.nombre_serv

ORDER BY total DESC";

//TABLA DE OPERADORES

$sqlOperadoresTabla = "SELECT

u.nombre_user,

COUNT(t.id_tickets) AS total,

SUM(
    CASE 
        WHEN t.estado_tk = 'CANCELADO'
        THEN 1
        ELSE 0
    END
) AS cancelados,

ROUND(
    AVG(
        TIMESTAMPDIFF(
            MINUTE,
            t.hora_atencion,
            t.hora_finalizado
        )
    )
) AS promedio

FROM tickets t

INNER JOIN usuarios u
ON t.id_usuario = u.id_usuario

WHERE " . rango('t.fecha_tk', $fechaInicio, $fechaFin) . "

GROUP BY u.id_usuario, u.nombre_user

ORDER BY total DESC";

//TABLA DE HORAS PICO

$sqlHorasTabla = "SELECT

DATE_FORMAT(creado_tk, '%H:00') AS hora,
COUNT(*) AS total

FROM tickets

WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin) . "

GROUP BY HOUR(creado_tk)

ORDER BY HOUR(creado_tk)";

//TABLA DE ESTADOS

$sqlEstados = "SELECT

estado_tk,
COUNT(*) AS total

FROM tickets

WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin) . "

GROUP BY estado_tk

ORDER BY total DESC";
