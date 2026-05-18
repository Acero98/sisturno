<?php

if(isset($_GET["id"]) && isset($_GET["accion"])){

    $id = intval($_GET["id"]);
    $accion = $_GET["accion"];

    if($accion == "desactivar"){
        $sql = $conexion->query("UPDATE usuarios 
                                SET estado_user = 0 
                                WHERE id_usuario = $id");

        header("Location: lista_operadores.php?mensaje=desactivado");
        exit();
    }

    if($accion == "activar"){
        $sql = $conexion->query("UPDATE usuarios 
                                SET estado_user = 1 
                                WHERE id_usuario = $id");

        header("Location: lista_operadores.php?mensaje=activado");
        exit();
    }

}