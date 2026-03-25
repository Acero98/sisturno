<?php
include "../modelo/conexion.php";

if(isset($_POST['generar_turno'])){

    $id_servicios = intval($_POST['id_servicios']);
    //$fecha = date("Y-m-d");

    // 🔹 Obtener último ticket del día
    $consulta = $conexion->query("SELECT MAX(numero_tk) as ultimo 
                                  FROM tickets 
                                  WHERE fecha_tk=CURRENT_DATE
                                  AND id_servicios = $id_servicios ");

    $dato = $consulta->fetch_object();

    $nuevo_ticket = $dato->ultimo ? $dato->ultimo + 1 : 1;

    // 🔹 Insertar ticket
    $conexion->query("INSERT INTO tickets (numero_tk, id_servicios, fecha_tk, estado_tk, creado_tk)
                      VALUES ($nuevo_ticket, $id_servicios, CURRENT_DATE, 'PENDIENTE', CURRENT_TIMESTAMP)");

    $ticket_formateado = str_pad($nuevo_ticket, 3, "0", STR_PAD_LEFT);

    // 🔹 Redirigir para evitar reenvío del formulario
    header("Location: ../vista/pantalla_seleccion.php?ticket=$ticket_formateado");
    exit();
}