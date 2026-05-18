<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {

    $id = $_POST["id"];
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $nombre = $_POST["nombre"];
    $dni  = $_POST["dni"];
    $genero  = $_POST["genero"];
    $puesto  = $_POST["puesto"];
    $oficina  = $_POST["oficina"];
    $observaciones  = $_POST["observaciones"];
    $rol = $_POST["rol"];
    $ventanilla  = $_POST["ventanilla"];

    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conexion->query("UPDATE usuarios 
                                SET usuario_user='$usuario',
                                    password_user='$passwordHash',
                                    nombre_user='$nombre',
                                    dni_user='$dni',
                                    genero_user='$genero',
                                    puesto_user='$puesto',
                                    oficina_user='$oficina',
                                    observaciones_user='$observaciones',
                                    id_rol_user=$rol,
                                    num_ventanilla='$ventanilla'
                                WHERE id_usuario=$id");
    } else {
        $sql = $conexion->query("UPDATE usuarios 
                                SET usuario_user='$usuario',
                                    nombre_user='$nombre',
                                    dni_user='$dni',
                                    genero_user='$genero',
                                    puesto_user='$puesto',
                                    oficina_user='$oficina',
                                    observaciones_user='$observaciones',
                                    id_rol_user=$rol,
                                    num_ventanilla='$ventanilla'
                                WHERE id_usuario=$id");
    }

    if (!$sql) {
        die("Error SQL: " . $conexion->error);
    }

    header("Location: lista_operadores.php?mensaje=actualizado");
    exit();
}
