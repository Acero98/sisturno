<?php

if (isset($_GET["id"]) && isset($_GET["accion"])) {

    $id = intval($_GET["id"]);
    $accion = $_GET["accion"];

    if ($accion == "desactivar") {
        $sql = $conexion->query("UPDATE servicios 
                                SET estado_serv = 0 
                                WHERE id_servicios = $id");

        //ignore_user_abort(true);
        //set_time_limit(0);

        // Notificar a todas las pantallas
        //include '../atencion/notificar_socket.php';

        header("Location: index.php?mensaje=desactivado");
        exit();
    }

    if ($accion == "activar") {
        $sql = $conexion->query("UPDATE servicios 
                                SET estado_serv = 1 
                                WHERE id_servicios = $id");

        //ignore_user_abort(true);
        //set_time_limit(0);
        // Notificar a todas las pantallas
        //include '../atencion/notificar_socket.php';

        header("Location: index.php?mensaje=activado");
        exit();
    }
}
