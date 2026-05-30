<?php

if (!empty($_POST["btnregistrarServicio"])) {

    if (!empty($_POST["nombre"]) && 
        !empty($_POST["codigo"]) && 
        isset($_POST["estado"]) && 
        $_POST["estado"] !== "" && 
        isset($_POST["prioridad"]) && 
        $_POST["prioridad"] !== "") {

        $nombre = $_POST["nombre"];
        $codigo = $_POST["codigo"];
        $estado  = $_POST["estado"];
        $prioridad  = $_POST["prioridad"];

        // Verificar si el servicio ya existe
        $verificar = $conexion->query("SELECT id_servicios FROM servicios WHERE codigo_serv='$codigo'");

        if ($verificar->num_rows > 0) {

            header("Location: index.php?mensaje=existe");
            exit();
        } else {

            $sql = $conexion->query("INSERT INTO servicios (nombre_serv, codigo_serv, estado_serv, prioridad_serv, creado_serv) 
                                    VALUES ('$nombre', '$codigo', '$estado', '$prioridad', CURRENT_TIMESTAMP)");

            if ($sql) {

                // Redirigir SOLO si insertó correctamente
                header("Location: index.php?mensaje=registrado");
                exit();
            } else {
                header("Location: index.php?mensaje=erroru");    
                //echo '<div class="alert alert-danger">Error al registrar usuario</div>';
            }
        }
    } else {
        header("Location: index.php?mensaje=erroro");
        //echo '<div class="alert alert-warning">Todos los campos son obligatorios</div>';
    }
}
