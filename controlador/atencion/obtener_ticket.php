<?php

    $id_usuario = $_SESSION['id_usuario'];

    $sql = "SELECT t.* FROM tickets t
    INNER JOIN operador_servicios os ON t.id_servicios = os.id_servicio
    WHERE os.id_usuario = ?
    AND t.estado_tk = 'PENDIENTE'
    AND t.fecha_tk = CURDATE()
    ORDER BY t.numero_tk ASC
    LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();

?>