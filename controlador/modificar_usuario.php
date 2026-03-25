<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {

    $id = $_POST["id"];
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $password = $_POST["password"];
    $rol = $_POST["rol"];

    // 🔎 VERIFICAR SI EL CÓDIGO YA EXISTE (EXCLUYENDO EL MISMO ID)
    $verificar = $conexion->query("SELECT id_usuario 
                                   FROM usuarios 
                                   WHERE usuario_user = '$usuario' 
                                   AND id_usuario != $id");

    if ($verificar->num_rows > 0) {
        header("Location: registrar_usuario.php?mensaje=existe");
        exit();
    }

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

    header("Location: registrar_usuario.php?mensaje=actualizado");
    exit();
}
?>