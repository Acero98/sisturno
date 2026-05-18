<?php
// admin/operador_servicios/guardar.php

include "../../modelo/conexion.php";
include "../../config.php";
//include "../../control/auth.php";
//include "../../control/permisos.php";

// ... guardar asignaciones ...

//permitirSolo(["Super Admin", "Admin"]);

/*
|--------------------------------------------------------------------------
| 1. Obtener el usuario seleccionado
|--------------------------------------------------------------------------
*/
$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;

/*
|--------------------------------------------------------------------------
| 2. Obtener los servicios marcados
|--------------------------------------------------------------------------
| Si no se marca ninguno, se recibe un arreglo vacío.
*/
$servicios = isset($_POST['servicios']) ? $_POST['servicios'] : [];

if ($id_usuario <= 0) {
    die("Usuario no válido.");
}

/*
|--------------------------------------------------------------------------
| 3. Eliminar asignaciones actuales del usuario
|--------------------------------------------------------------------------
| Esto permite actualizar la selección completa cada vez que se guarda.
*/
$conexion->query("
    DELETE FROM operador_servicios
    WHERE id_usuario = $id_usuario
");

/*
|--------------------------------------------------------------------------
| 4. Insertar nuevamente los servicios seleccionados
|--------------------------------------------------------------------------
*/
foreach ($servicios as $id_servicio) {
    $id_servicio = (int)$id_servicio;

    $conexion->query("
        INSERT INTO operador_servicios (id_usuario, id_servicio)
        VALUES ($id_usuario, $id_servicio)
    ");
}

include "../atencion/notificar_socket.php";

/*
|--------------------------------------------------------------------------
| 5. Redireccionar de regreso a la misma página
|--------------------------------------------------------------------------
*/
header("Location: " . BASE_URL . "vista/servuser/index.php?id_usuario=" . $id_usuario);
exit;
