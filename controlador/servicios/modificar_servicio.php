<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $codigo = $_POST["codigo"];

    // VERIFICAR SI EL CÓDIGO YA EXISTE (EXCLUYENDO EL MISMO ID)
    $verificar = $conexion->query("SELECT id_servicios 
                                    FROM servicios 
                                    WHERE codigo_serv = '$codigo' 
                                    AND id_servicios != $id");

    if ($verificar->num_rows > 0) {
        header("Location: index.php?mensaje=existe");
        exit();
    }

    // ACTUALIZAR
    if (!empty($codigo)) {
        $sql = $conexion->query("UPDATE servicios 
                                    SET nombre_serv='$nombre',
                                        codigo_serv='$codigo'
                                    WHERE id_servicios=$id");
    }

    if (!$sql) {
        die("Error SQL: " . $conexion->error);
    }

    header("Location: index.php?mensaje=actualizado");
    exit();
}
