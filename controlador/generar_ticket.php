<?php
require_once __DIR__ . "/../modelo/conexion.php";
require_once __DIR__ . "/../config.php";

if (isset($_POST['generar_turno'])) {

    $id_servicios = intval($_POST['id_servicios']);

    // ==========================================================
    // 1. OBTENER EL CÓDIGO DEL SERVICIO (Ejemplo: CONU, DUPL)
    // ==========================================================
    $sqlServicio = $conexion->query("
    SELECT codigo_serv,
           prioridad_serv,
           nombre_serv
    FROM servicios
    WHERE id_servicios = $id_servicios
    LIMIT 1
");

    $servicio = $sqlServicio->fetch_object();

    if (!$servicio) {
        die("El servicio seleccionado no existe.");
    }

    $codigo_serv = $servicio->codigo_serv;
$prioridad   = $servicio->prioridad_serv;
$nombre_serv = $servicio->nombre_serv;

    // ==========================================================
    // 2. OBTENER EL ÚLTIMO TICKET DEL DÍA PARA ESE SERVICIO
    //    numero_tk ahora guarda valores como:
    //    CONU-001, CONU-010, DUPL-014
    // ==========================================================
    $consulta = $conexion->query("SELECT numero_tk FROM tickets WHERE fecha_tk = CURRENT_DATE AND id_servicios = $id_servicios ORDER BY id_tickets DESC LIMIT 1");

    $nuevo_numero = 1; // Valor inicial

    if ($consulta && $consulta->num_rows > 0) {
        $dato = $consulta->fetch_object();

        // Ejemplo: CONU-010
        $ultimo_ticket = $dato->numero_tk;

        // Extraer la parte numérica después del guion
        // CONU-010 -> 010
        $partes = explode('-', $ultimo_ticket);

        if (isset($partes[1])) {
            // Convertir "010" a 10 y sumar 1
            $nuevo_numero = intval($partes[1]) + 1;
        }
    }


    // ==========================================================
    // 3. FORMATEAR EL NÚMERO CON CEROS A LA IZQUIERDA
    // ==========================================================
    $numero_formateado = str_pad($nuevo_numero, 3, "0", STR_PAD_LEFT);


    // ==========================================================
    // 4. CREAR EL TICKET COMPLETO
    // ==========================================================
    $ticket_formateado = $codigo_serv . '-' . $numero_formateado;


    // ==========================================================
    // 5. INSERTAR EN LA BASE DE DATOS
    // ==========================================================
    $conexion->query("INSERT INTO tickets (numero_tk, id_servicios, fecha_tk, estado_tk, prioridad_tk, creado_tk) 
                    VALUES ('$ticket_formateado', $id_servicios, CURRENT_DATE, 'PENDIENTE','$prioridad', CURRENT_TIMESTAMP)");

    // ==========================================================
    // 5.1 NOTIFICAR AL WEBSOCKET
    // ==========================================================
    //include "atencion/notificar_socket.php";

    // ==========================================================
    // 5.1 NOTIFICAR AL WEBSOCKET (ACTUALIZAR PANTALLAS + IMPRIMIR)
    // ==========================================================
    $datos = [
        "ticket" => $ticket_formateado,
        "servicio" => $nombre_serv,
        "tipo"   => "nuevo_ticket"
    ];

    $options = [
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json\r\n",
            "content" => json_encode($datos)
        ]
    ];

    $context = stream_context_create($options);

    @file_get_contents(
        SOCKETURL."/notificar",
        //"http://192.168.0.6:3000/notificar",
        false,
        $context
    );

    // ==========================================================
    // 6. DEVOLVER EL TICKET AL JAVASCRIPT
    // ==========================================================
    echo $ticket_formateado;
    exit();

    // ==========================================================
    // 6. REDIRIGIR MOSTRANDO EL TICKET GENERADO
    // ==========================================================
    //header("Location: ../vista/pantalla_seleccion.php?ticket=" . urlencode($ticket_formateado));
    //exit();
}
