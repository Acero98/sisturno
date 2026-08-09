<?php
require_once __DIR__ . '/../../modelo/conexion.php';
require_once __DIR__ . '/../../control/auth.php';
require_once __DIR__ . '/../../control/permisos.php';
permitirSolo(['Super Admin', 'Admin']);
$inicio = $_GET['inicio'] ?? date('Y-m-d'); $fin = $_GET['fin'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $inicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fin) || $inicio > $fin) exit('Rango de fechas no válido.');
$stmt = $conexion->prepare('SELECT s.nombre_serv, s.codigo_serv, COUNT(t.id_tickets) finalizados, AVG(TIMESTAMPDIFF(MINUTE,t.hora_atencion,t.hora_finalizado)) promedio FROM tickets t INNER JOIN servicios s ON t.id_servicios=s.id_servicios WHERE t.estado_tk="FINALIZADO" AND t.fecha_tk BETWEEN ? AND ? AND t.hora_atencion IS NOT NULL AND t.hora_finalizado IS NOT NULL GROUP BY s.id_servicios,s.nombre_serv,s.codigo_serv ORDER BY promedio');
$stmt->bind_param('ss', $inicio, $fin); $stmt->execute(); $filas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
header('Content-Type: application/vnd.ms-excel; charset=UTF-8'); header('Content-Disposition: attachment; filename="tiempo_promedio_servicios.xls"');
?><table border="1"><tr><th>Servicio</th><th>Código</th><th>Finalizados</th><th>Promedio (min)</th></tr><?php foreach($filas as $fila): ?><tr><td><?= htmlspecialchars($fila['nombre_serv']) ?></td><td><?= htmlspecialchars($fila['codigo_serv']) ?></td><td><?= $fila['finalizados'] ?></td><td><?= round($fila['promedio']) ?></td></tr><?php endforeach; ?></table>
