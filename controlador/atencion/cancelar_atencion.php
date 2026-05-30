<?php
session_start();
require_once __DIR__ . "/../../modelo/conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    exit("ERROR");
}

$id_usuario = intval($_SESSION['id_usuario']);

/*
|--------------------------------------------------------------------------
| BUSCAR EL TICKET ACTIVO DEL USUARIO
|--------------------------------------------------------------------------
| Se considera activo si está en:
| - LLAMADO
| - EN_ATENCION
|--------------------------------------------------------------------------
*/
$sql = "SELECT id_tickets
        FROM tickets
        WHERE id_usuario = $id_usuario
        AND estado_tk IN ('LLAMADO', 'EN_ATENCION')
        AND fecha_tk = CURRENT_DATE
        ORDER BY creado_tk ASC
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
| ACTUALIZAR EL ESTADO A CANCELADO
|--------------------------------------------------------------------------
*/
$update = "UPDATE tickets
            SET estado_tk = 'CANCELADO',
                hora_finalizado = CURRENT_TIMESTAMP
            WHERE id_tickets = $id_ticket
            AND id_usuario = $id_usuario
        ";

if ($conexion->query($update)) {
    include 'notificar_socket.php';
    echo "OK";
} else {
    echo "ERROR";
}
