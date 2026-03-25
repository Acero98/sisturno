<?php
require_once __DIR__ . "/../config.php";

function permitirSolo($rolesPermitidos = []){

    if(!isset($_SESSION['rol'])){
        header("Location: " . BASE_URL . "login.php");
        exit();
    }

    if(!in_array($_SESSION['rol'], $rolesPermitidos)){

        // Si es Monitor lo mandamos a su pantalla
        if($_SESSION['rol'] === "Monitor"){
            header("Location: " . BASE_URL . "vista/pantalla_seleccion.php");
            exit();
        }elseif($_SESSION['rol'] === "Operador"){
            header("Location: " . BASE_URL . "vista/atencion/atencion_cliente.php");
            exit();
        }

        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}