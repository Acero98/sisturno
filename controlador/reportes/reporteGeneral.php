<?php

//include "../modelo/conexion.php";

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
    return "$campo BETWEEN '$inicio 00:00:00' AND '$fin 23:59:59'";
}

// =========================
// MÉTRICAS REALES
// =========================
// Total de tickets generados HOY
$total = $conexion->query(
    "SELECT COUNT(*) AS total 
    FROM tickets 
    WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets pendientes HOY
$pendientes = $conexion->query(
    "SELECT COUNT(*) AS total 
                                FROM tickets 
                                WHERE estado_tk = 'PENDIENTE' 
                                AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets llamado HOY
$llamados = $conexion->query(
    "SELECT COUNT(*) AS total 
                                FROM tickets 
                                WHERE estado_tk = 'LLAMADO' 
                                AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets en atencion HOY
$en_atencion = $conexion->query(
    "SELECT COUNT(*) AS total 
                                    FROM tickets 
                                    WHERE estado_tk = 'EN_ATENCION' 
                                    AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets finalizados HOY
$atendidos = $conexion->query(
    "SELECT COUNT(*) AS total 
                                FROM tickets 
                                WHERE estado_tk = 'FINALIZADO' 
                                AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

// Tickets cancelados HOY
$cancelados = $conexion->query(
    "SELECT COUNT(*) AS total 
                                FROM tickets 
                                WHERE estado_tk = 'CANCELADO' 
                                AND " . rango('fecha_tk', $fechaInicio, $fechaFin)
)->fetch_object()->total;

$fechaActual = date("d/m/Y");
$horaActual = date("h:i A");

// =====================================================
// DATOS PARA GRÁFICO ESTADOS
// =====================================================

$estadoLabels = [];
$estadoData = [];

// CONSULTA ESTADOS
$sqlEstados = "

SELECT

estado_tk,
COUNT(*) as total

FROM tickets

WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin) . "

GROUP BY estado_tk

ORDER BY total DESC

";

$graficoEstados = $conexion->query($sqlEstados);

while ($row = $graficoEstados->fetch_assoc()) {

    $estadoLabels[] = $row['estado_tk'];

    $estadoData[] = (int)$row['total'];
}

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

// =====================================================
// TIEMPO PROMEDIO DE ATENCIÓN
// =====================================================

$sqlTiempoAtencion = "SELECT 
    AVG(TIMESTAMPDIFF(MINUTE, hora_atencion, hora_finalizado)) AS promedio_atencion
FROM tickets
WHERE estado_tk = 'FINALIZADO'
AND " . rango('fecha_tk', $fechaInicio, $fechaFin) . "
AND hora_atencion IS NOT NULL
AND hora_finalizado IS NOT NULL";

$resultTiempoAtencion = $conexion->query($sqlTiempoAtencion);

$promedioAtencion = 0;

if ($resultTiempoAtencion && $row = $resultTiempoAtencion->fetch_assoc()) {

    $promedioAtencion = $row['promedio_atencion'] !== null
        ? round($row['promedio_atencion'])
        : 0;
}

// =====================================================
// TOP 5 PROCESOS MÁS UTILIZADOS EN LA FECHA ACTUAL
// =====================================================
// Se asume:
// - La tabla tickets tiene la columna id_proceso
// - La tabla procesos tiene: id_proceso, nombre_proceso, codigo_proceso
// - La columna fecha_registro almacena la fecha del ticket
// =====================================================

$sqlTopServicios = "SELECT s.nombre_serv, s.codigo_serv, COUNT(t.id_tickets) AS total_atenciones 
                    FROM tickets t 
                    INNER JOIN servicios s 
                    ON t.id_servicios = s.id_servicios
                    WHERE " . rango('t.fecha_tk', $fechaInicio, $fechaFin) . "
                    GROUP BY t.id_servicios, s.nombre_serv, s.codigo_serv
                    ORDER BY total_atenciones DESC
                    LIMIT 5
                ";

$topServicios = $conexion->query($sqlTopServicios);

// =====================================================
// DATOS PARA GRÁFICO SERVICIOS
// =====================================================

$serviciosLabels = [];
$serviciosData = [];

// Nueva consulta SOLO para gráfico
$graficoServicios = $conexion->query($sqlTopServicios);

while ($row = $graficoServicios->fetch_assoc()) {

    $serviciosLabels[] = $row['nombre_serv'];
    $serviciosData[] = (int)$row['total_atenciones'];
}

// =====================================================
// TOP 5 EMPLEADOS CON MÁS ATENCIONES EN LA FECHA ACTUAL
// =====================================================
// Se asume:
// - La tabla tickets tiene la columna id_usuario
// - La tabla usuarios tiene: id_usuario, usuario, codigo_usuario
// - La columna fecha_registro almacena la fecha del ticket
// =====================================================

$sqlTopEmpleados = "SELECT u.nombre_user AS nombre_empleado, u.dni_user, COUNT(t.id_tickets) AS total_atenciones 
                    FROM tickets t
                    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
                    WHERE " . rango('t.fecha_tk', $fechaInicio, $fechaFin) . " 
                    AND t.estado_tk = 'FINALIZADO'
                    GROUP BY u.id_usuario, u.nombre_user, u.dni_user
                    ORDER BY total_atenciones DESC
                    LIMIT 5
                ";

$topEmpleados = $conexion->query($sqlTopEmpleados);

// =====================================================
// TOP OPERADORES CON TIEMPO PROMEDIO
// =====================================================

$sqlRankingOperadores = "
SELECT 
    u.nombre_user,
    COUNT(t.id_tickets) AS total_atenciones,
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
    IFNULL(
        ROUND(
            AVG(
                CASE
                    WHEN t.hora_atencion IS NOT NULL
                    AND t.hora_finalizado IS NOT NULL
                    THEN TIMESTAMPDIFF(
                        MINUTE,
                        t.hora_atencion,
                        t.hora_finalizado
                    )
                END
            )
        ),
        0
    ) AS promedio_atencion
FROM tickets t
INNER JOIN usuarios u 
ON t.id_usuario = u.id_usuario
WHERE " . rango('t.fecha_tk', $fechaInicio, $fechaFin) . "
GROUP BY u.id_usuario, u.nombre_user
ORDER BY total_atenciones DESC
LIMIT 6";

$rankingOperadores = $conexion->query($sqlRankingOperadores);

// =====================================================
// TENDENCIA - TICKETS
// =====================================================

$dias = (strtotime($fechaFin) - strtotime($fechaInicio)) / 86400;

if ($dias <= 1) {

    // HOY → por hora
    $groupBy = "DATE_FORMAT(creado_tk, '%H:00')";
    $labelSQL = "DATE_FORMAT(creado_tk, '%H:00')";
} elseif ($dias <= 31) {

    // SEMANA / MES → por día
    $groupBy = "DATE_FORMAT(fecha_tk, '%d/%m')";
    $labelSQL = "DATE_FORMAT(fecha_tk, '%d/%m')";
} elseif ($dias <= 90) {

    // 2-3 meses → por semana
    $groupBy = "YEARWEEK(fecha_tk)";
    $labelSQL = "YEARWEEK(fecha_tk)";
} else {

    // AÑO → por mes
    $groupBy = "DATE_FORMAT(fecha_tk, '%Y-%m')";
    $labelSQL = "DATE_FORMAT(fecha_tk, '%Y-%m')";
}

$sqlTendencia = "SELECT 
    $labelSQL AS periodo,
    COUNT(*) AS total

FROM tickets

WHERE " . rango('fecha_tk', $fechaInicio, $fechaFin) . "

GROUP BY $groupBy

ORDER BY $groupBy ASC";

$tendencia = $conexion->query($sqlTendencia);

$tendenciaLabels = [];
$tendenciaData = [];

while ($row = $tendencia->fetch_assoc()) {

    if ($dias > 31 && $dias <= 90) {

        $tendenciaLabels[] = 'Sem ' . substr($row['periodo'], -2);
    } elseif ($dias > 90) {

        $meses = [
            'Jan' => 'Ene',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'May' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Sep',
            'Oct' => 'Oct',
            'Nov' => 'Nov',
            'Dec' => 'Dic'
        ];

        $fechaFormateada = date(
            'M Y',
            strtotime($row['periodo'] . '-01')
        );

        $mesEn = date(
            'M',
            strtotime($row['periodo'] . '-01')
        );

        $fechaFormateada = str_replace(
            $mesEn,
            $meses[$mesEn],
            $fechaFormateada
        );

        $tendenciaLabels[] = $fechaFormateada;
    } else {

        $tendenciaLabels[] = $row['periodo'];
    }
    $tendenciaData[] = (int)$row['total'];
}

// =====================================================
// HORAS PICO
// =====================================================

$sqlHorasPico = "SELECT 
                    HOUR(creado_tk) AS hora,
                    COUNT(*) AS total
                FROM tickets
                WHERE " . rango('creado_tk', $fechaInicio, $fechaFin) . "
                GROUP BY HOUR(creado_tk)
                ORDER BY hora ASC";

$horasPico = $conexion->query($sqlHorasPico);

$horasLabels = [];
$horasData = [];

while ($row = $horasPico->fetch_assoc()) {

    $horaFormateada = str_pad($row['hora'], 2, "0", STR_PAD_LEFT) . ":00";

    $horasLabels[] = $horaFormateada;
    $horasData[] = (int)$row['total'];
}
