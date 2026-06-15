<?php

$hoy = date('Y-m-d');

$sql = "SELECT

COUNT(*) AS total_tickets,

SUM(
    CASE
        WHEN estado_tk = 'EN_ATENCION'
        THEN 1
        ELSE 0
    END
) AS en_atencion,

SUM(
    CASE
        WHEN estado_tk = 'FINALIZADO'
        THEN 1
        ELSE 0
    END
) AS finalizados,

SUM(
    CASE
        WHEN estado_tk = 'CANCELADO'
        THEN 1
        ELSE 0
    END
) AS cancelados

FROM tickets

WHERE id_usuario = ?
AND DATE(fecha_tk) = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("is", $id_usuario, $hoy);
$stmt->execute();

$estadisticas = $stmt->get_result()->fetch_assoc();

//PROMEDIO DE ATENCION

$sqlPromedio = "
SELECT
AVG(
    TIMESTAMPDIFF(
        MINUTE,
        hora_atencion,
        hora_finalizado
    )
) AS promedio_atencion
FROM tickets
WHERE id_usuario = ?
AND estado_tk = 'FINALIZADO'
AND DATE(hora_finalizado)=?
";

$stmt = $conexion->prepare($sqlPromedio);
$stmt->bind_param("is", $id_usuario, $hoy);
$stmt->execute();

$promedio = $stmt->get_result()->fetch_assoc();

//PROMEDIO DE ESPERA DE ATENCION

$sqlEspera = "
SELECT
AVG(
    TIMESTAMPDIFF(
        MINUTE,
        hora_cita,
        hora_atencion
    )
) AS promedio_espera
FROM tickets
WHERE id_usuario = ?
AND hora_atencion IS NOT NULL
AND DATE(hora_atencion) = ?
";

$stmt = $conexion->prepare($sqlEspera);
$stmt->bind_param("is", $id_usuario, $hoy);
$stmt->execute();

$espera = $stmt->get_result()->fetch_assoc();