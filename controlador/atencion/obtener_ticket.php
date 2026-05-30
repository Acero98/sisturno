<?php

$tickets = [];   // Lista de espera (columna izquierda)
$ticket  = null; // Ticket actual del operador (parte superior)

// Verificar que exista el usuario en sesión
if (!isset($_SESSION['id_usuario'])) {
    return;
}

$id_usuario = intval($_SESSION['id_usuario']);

/*
|--------------------------------------------------------------------------
| A) BUSCAR SI EL OPERADOR YA TIENE UN TICKET ACTIVO
|--------------------------------------------------------------------------
| Se consideran activos los estados:
| - LLAMADO
| - EN_ATENCION
|
| Si existe un ticket con estos estados y asignado al usuario actual,
| ese será el ticket que se mostrará en la parte superior.
|--------------------------------------------------------------------------
*/
$sql_actual = "SELECT t.id_tickets, t.numero_tk, t.estado_tk, s.nombre_serv
                FROM tickets t
                INNER JOIN servicios s ON t.id_servicios = s.id_servicios
                WHERE t.id_usuario = $id_usuario
                AND t.estado_tk IN ('LLAMADO', 'EN_ATENCION')
                AND t.fecha_tk = CURRENT_DATE
                ORDER BY t.creado_tk ASC
                LIMIT 1
            ";

$resultado_actual = $conexion->query($sql_actual);

if (!$resultado_actual) {
    die("Error al obtener ticket actual: " . $conexion->error);
}

// Si el operador ya tiene un ticket activo, lo guardamos en $ticket
if ($resultado_actual->num_rows > 0) {
    $fila = $resultado_actual->fetch_assoc();

    $ticket = [
        'id_tickets' => $fila['id_tickets'],
        'numero_tk'  => $fila['numero_tk'],
        'estado_tk'  => $fila['estado_tk'],
        'servicio'   => $fila['nombre_serv']
    ];
}

/*
|--------------------------------------------------------------------------
| B) OBTENER LA LISTA DE ESPERA
|--------------------------------------------------------------------------
| Solo se muestran tickets PENDIENTE.
| Los tickets LLAMADO o EN_ATENCION ya no aparecerán aquí.
|--------------------------------------------------------------------------
*/
$sql_lista = "SELECT t.id_tickets, t.numero_tk, t.estado_tk, s.nombre_serv, s.prioridad_serv
                FROM tickets t 
                INNER JOIN operador_servicios os 
                    ON t.id_servicios = os.id_servicio
                INNER JOIN servicios s 
                    ON t.id_servicios = s.id_servicios
                WHERE os.id_usuario = $id_usuario
                AND t.estado_tk = 'PENDIENTE'
                AND t.fecha_tk = CURRENT_DATE
                ORDER BY
                    CASE
                        WHEN s.prioridad_serv = 'EMERGENCIA' THEN 1
                        WHEN s.prioridad_serv = 'ALTA' THEN 2
                        WHEN s.prioridad_serv = 'NORMAL' THEN 3
                        ELSE 4
                    END,
                    t.creado_tk ASC
                ";

$resultado_lista = $conexion->query($sql_lista);

if (!$resultado_lista) {
    die("Error al obtener lista de espera: " . $conexion->error);
}

$contador = 1;

while ($fila = $resultado_lista->fetch_assoc()) {
    $tickets[] = [
        'numero'      => $contador++,
        'estado'      => $fila['estado_tk'],
        'ticket'      => $fila['numero_tk'],
        'servicio'    => $fila['nombre_serv'],
        'id_tickets'  => $fila['id_tickets']
    ];
}

/*
|--------------------------------------------------------------------------
| C) SI EL OPERADOR NO TIENE TICKET ACTIVO,
|    TOMAMOS EL PRIMER TICKET PENDIENTE PARA MOSTRAR "LLAMAR A ..."
|--------------------------------------------------------------------------
*/
if ($ticket === null && !empty($tickets)) {
    $ticket = [
        'id_tickets' => $tickets[0]['id_tickets'],
        'numero_tk'  => $tickets[0]['ticket'],
        'estado_tk'  => $tickets[0]['estado'],
        'servicio'   => $tickets[0]['servicio']
    ];
}
