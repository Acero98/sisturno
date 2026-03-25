<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {

    $id = $_POST["id"];
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $password = $_POST["password"];
    $rol = $_POST["rol"];

    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conexion->query("UPDATE usuarios 
                                 SET usuario_user='$usuario',
                                     nombre_user='$nombre',
                                     password_user='$passwordHash',
                                     id_rol_user=$rol
                                 WHERE id_usuario=$id");
    } else {
        $sql = $conexion->query("UPDATE usuarios 
                                 SET usuario_user='$usuario',
                                     nombre_user='$nombre',
                                     id_rol_user=$rol
                                 WHERE id_usuario=$id");
    }

    if (!$sql) {
            die("Error SQL: " . $conexion->error);
        }

    header("Location: lista_operadores.php?mensaje=actualizado");
    exit();
}
?>