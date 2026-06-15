<?php

$hoy = date('Y-m-d');

$sqlServicios = "
SELECT
    s.nombre_serv,
    COUNT(*) AS cantidad
FROM tickets t
INNER JOIN servicios s
    ON s.id_servicios = t.id_servicios
WHERE t.id_usuario = ?
AND DATE(t.fecha_tk) = ?
GROUP BY s.id_servicios, s.nombre_serv
ORDER BY cantidad DESC
";

$stmt = $conexion->prepare($sqlServicios);
$stmt->bind_param("is", $id_usuario, $hoy);
$stmt->execute();

$servicios = $stmt->get_result();