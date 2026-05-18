<?php

if (!empty($_POST["btnregistrarUsuario"])) {

    if (!empty($_POST["usuario"]) && 
        !empty($_POST["password"]) && 
        !empty($_POST["nombre"]) &&
        !empty($_POST["rol"])) {

        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $nombre  = $_POST["nombre"];
        $rol  = $_POST["rol"];

        // Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Verificar si el usuario ya existe
        $verificar = $conexion->query("SELECT id_usuario FROM usuarios WHERE usuario_user='$usuario'");

        if ($verificar->num_rows > 0) {

            header("Location: registrar_usuario.php?mensaje=existe");
            exit();

        } else {

            $sql = $conexion->query("INSERT INTO usuarios (usuario_user, password_user, nombre_user, id_rol_user) 
                                    VALUES ('$usuario', '$passwordHash', '$nombre', '$rol')");

            if ($sql) {

                // Redirigir SOLO si insertó correctamente
                header("Location: registrar_usuario.php?mensaje=registrado");
                exit();

            } else {

                echo '<div class="alert alert-danger">Error al registrar usuario</div>';
            }
        }

    } else {
        echo '<div class="alert alert-warning">Todos los campos son obligatorios</div>';
    }
}
?>