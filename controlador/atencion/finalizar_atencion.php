<?php
session_start();
require "../../modelo/conexion.php";

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    exit("ERROR");
}

$id_usuario = intval($_SESSION['id_usuario']);

/*
|--------------------------------------------------------------------------
| BUSCAR EL TICKET EN ATENCIÓN DEL USUARIO ACTUAL
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT id_tickets
    FROM tickets
    WHERE id_usuario = $id_usuario
      AND estado_tk = 'EN_ATENCION'
      AND fecha_tk = CURRENT_DATE
    ORDER BY hora_cita ASC
    LIMIT 1
";

$resultado = $conexion->query($sql);

if (!$resultado || $resultado->num_rows == 0) {
    exit("ERROR");
}

$fila = $resultado->fetch_assoc();
$id_ticket = intval($fila['id_tickets']);

/*
|--------------------------------------------------------------------------
| ACTUALIZAR EL ESTADO A FINALIZADO
|--------------------------------------------------------------------------
*/
$update = "
    UPDATE tickets
    SET estado_tk = 'FINALIZADO',
        hora_finalizado = CURRENT_TIMESTAMP
    WHERE id_tickets = $id_ticket
      AND id_usuario = $id_usuario
      AND estado_tk = 'EN_ATENCION'
";

if ($conexion->query($update)) {
    include 'notificar_socket.php';
    echo "OK";
} else {
    echo "ERROR";
}
?>