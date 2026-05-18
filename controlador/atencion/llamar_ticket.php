<?php
session_start();
require "../../modelo/conexion.php";
//include 'notificar_socket.php';

if (!isset($_SESSION['id_usuario'])) {
    exit("ERROR");
}

$id_usuario = intval($_SESSION['id_usuario']);

/*
|--------------------------------------------------------------------------
| BUSCAR EL PRIMER TICKET PENDIENTE DE LOS SERVICIOS ASIGNADOS AL USUARIO
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT t.id_tickets
    FROM tickets t
    INNER JOIN operador_servicios os
        ON t.id_servicios = os.id_servicio
    WHERE os.id_usuario = $id_usuario
      AND t.estado_tk = 'PENDIENTE'
      AND t.fecha_tk = CURRENT_DATE
    ORDER BY t.creado_tk ASC
    LIMIT 1
";

$resultado = $conexion->query($sql);

if (!$resultado || $resultado->num_rows == 0) {
    exit("SIN_TICKETS");
}

$ticket = $resultado->fetch_assoc();
$id_ticket = intval($ticket['id_tickets']);

/*
|--------------------------------------------------------------------------
| ACTUALIZAR EL ESTADO DEL TICKET
|--------------------------------------------------------------------------
*/
$update = "
    UPDATE tickets
    SET estado_tk = 'LLAMADO',
        id_usuario = $id_usuario,
        hora_cita = CURRENT_TIMESTAMP
    WHERE id_tickets = $id_ticket
    AND estado_tk = 'PENDIENTE'
";

if ($conexion->query($update)) {
    include 'notificar_socket.php';
    echo "OK";
} else {
    echo "ERROR";
}
?>