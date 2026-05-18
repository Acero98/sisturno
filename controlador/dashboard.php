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
