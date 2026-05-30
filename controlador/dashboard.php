<?php

//include "../modelo/conexion.php";
// =========================
// MÉTRICAS REALES
// =========================
// Total de tickets generados HOY
$total = $conexion->query("SELECT COUNT(*) AS total FROM tickets WHERE DATE(fecha_tk) = CURDATE()")->fetch_object()->total;

// Tickets pendientes HOY
$pendientes = $conexion->query("SELECT COUNT(*) AS total FROM tickets WHERE estado_tk = 'PENDIENTE' AND DATE(fecha_tk) = CURDATE()")->fetch_object()->total;

// Tickets llamado HOY
$llamados = $conexion->query("SELECT COUNT(*) AS total FROM tickets WHERE estado_tk = 'LLAMADO' AND DATE(fecha_tk) = CURDATE()")->fetch_object()->total;

// Tickets en atencion HOY
$en_atencion = $conexion->query("SELECT COUNT(*) AS total FROM tickets WHERE estado_tk = 'EN_ATENCION' AND DATE(fecha_tk) = CURDATE()")->fetch_object()->total;

// Tickets finalizados HOY
$atendidos = $conexion->query("SELECT COUNT(*) AS total FROM tickets WHERE estado_tk = 'FINALIZADO' AND DATE(fecha_tk) = CURDATE()")->fetch_object()->total;

// Tickets cancelados HOY
$cancelados = $conexion->query("SELECT COUNT(*) AS total FROM tickets WHERE estado_tk = 'CANCELADO' AND DATE(fecha_tk) = CURDATE()")->fetch_object()->total;

$fechaActual = date("d/m/Y");
$horaActual = date("h:i A");

// =====================================================
// TIEMPO PROMEDIO DE ESPERA
// =====================================================

$sqlTiempoEspera = "SELECT 
    AVG(TIMESTAMPDIFF(MINUTE, creado_tk, hora_cita)) AS promedio_espera
FROM tickets
WHERE estado_tk IN ('LLAMADO', 'EN_ATENCION', 'FINALIZADO')
AND DATE(fecha_tk) = CURDATE()
AND hora_cita IS NOT NULL";

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
AND DATE(fecha_tk) = CURDATE()
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

$sqlTopServicios = "SELECT s.nombre_serv, s.codigo_serv, COUNT(t.id_tickets) 
                    AS total_atenciones FROM tickets t 
                    INNER JOIN servicios s 
                    ON t.id_servicios = s.id_servicios
                    WHERE DATE(t.fecha_tk) = CURDATE()
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

$sqlTopEmpleados = "SELECT u.nombre_user AS nombre_empleado, u.dni_user, COUNT(t.id_tickets) AS total_atenciones FROM tickets t
                    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
                    WHERE DATE(t.fecha_tk) = CURDATE() AND t.estado_tk = 'FINALIZADO'
                    GROUP BY u.id_usuario, u.nombre_user, u.dni_user
                    ORDER BY total_atenciones DESC
                    LIMIT 5
                ";

$topEmpleados = $conexion->query($sqlTopEmpleados);

// =====================================================
// TOP OPERADORES CON TIEMPO PROMEDIO
// =====================================================

$sqlRankingOperadores = "SELECT 
    u.nombre_user,
    COUNT(t.id_tickets) AS total_atenciones,

    AVG(
        TIMESTAMPDIFF(
            MINUTE,
            t.hora_atencion,
            t.hora_finalizado
        )
    ) AS promedio_atencion

FROM tickets t

INNER JOIN usuarios u 
ON t.id_usuario = u.id_usuario

WHERE t.estado_tk = 'FINALIZADO'
AND DATE(t.fecha_tk) = CURDATE()

AND t.hora_atencion IS NOT NULL
AND t.hora_finalizado IS NOT NULL

GROUP BY u.id_usuario, u.nombre_user

ORDER BY total_atenciones DESC

LIMIT 10";

$rankingOperadores = $conexion->query($sqlRankingOperadores);

// =====================================================
// TENDENCIA - TICKETS ÚLTIMOS 7 DÍAS
// =====================================================

$sqlTendencia = "SELECT 
    DATE(fecha_tk) AS fecha,
    COUNT(*) AS total

FROM tickets

WHERE fecha_tk >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)

GROUP BY DATE(fecha_tk)

ORDER BY fecha ASC";

$tendencia = $conexion->query($sqlTendencia);

$tendenciaLabels = [];
$tendenciaData = [];

while ($row = $tendencia->fetch_assoc()) {

    $tendenciaLabels[] = date(
        "d/m",
        strtotime($row['fecha'])
    );

    $tendenciaData[] = (int)$row['total'];
}

// =====================================================
// HORAS PICO
// =====================================================

$sqlHorasPico = "SELECT 
                    HOUR(creado_tk) AS hora,
                    COUNT(*) AS total
                FROM tickets
                WHERE DATE(fecha_tk) = CURDATE()
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
