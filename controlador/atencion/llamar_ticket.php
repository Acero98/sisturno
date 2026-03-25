<?php

require "../../modelo/conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "ERROR";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$conexion->begin_transaction();

try {

    // 1️⃣ Buscar siguiente ticket pendiente del operador
    $stmt = $conexion->prepare("
        SELECT t.id_tickets
        FROM tickets t
        INNER JOIN operador_servicios os 
            ON t.id_servicios = os.id_servicio
        WHERE os.id_usuario = ?
        AND t.estado_tk = 'PENDIENTE'
        AND t.fecha_tk = CURDATE()
        ORDER BY t.numero_tk ASC
        LIMIT 1
        FOR UPDATE
    ");

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();

    if (!$ticket) {
        throw new Exception("No hay tickets pendientes");
    }

    $id_ticket = $ticket['id_tickets'];

    // 2️⃣ Actualizar estado a LLAMADO
    $update = $conexion->prepare("
        UPDATE tickets
        SET estado_tk = 'LLAMADO',
            id_usuario_atendio = ?,
            hora_llamado = NOW()
        WHERE id_tickets = ?
    ");

    $update->bind_param("ii", $id_usuario, $id_ticket);
    $update->execute();

    $conexion->commit();

    echo "OK";

} catch (Exception $e) {

    $conexion->rollback();
    echo "ERROR";
}