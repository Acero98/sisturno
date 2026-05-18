<?php

if (!empty($_POST["btnregistrarUsuario"])) {

    if (!empty($_POST["usuario"]) && 
        !empty($_POST["password"]) && 
        !empty($_POST["nombre"]) &&
        !empty($_POST["rol"])) {

        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $nombre  = $_POST["nombre"];
        $dni  = $_POST["dni"];
        $genero  = $_POST["genero"];
        $puesto  = $_POST["puesto"];
        $oficina  = $_POST["oficina"];
        $observaciones  = $_POST["observaciones"];
        $rol  = $_POST["rol"];
        $ventanilla  = $_POST["ventanilla"];

        // Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Verificar si el usuario ya existe
        $verificar = $conexion->query("SELECT id_usuario, dni_user FROM usuarios WHERE usuario_user='$usuario' OR dni_user='$dni'");

        if ($verificar->num_rows > 0) {

            header("Location: lista_operadores.php?mensaje=existe");
            //echo '<div class="alert alert-warning">El usuario ya existe</div>';

        } else {

            $sql = $conexion->query("INSERT INTO usuarios (
                                                usuario_user, 
                                                password_user, 
                                                nombre_user, 
                                                dni_user, 
                                                genero_user, 
                                                puesto_user, 
                                                oficina_user, 
                                                observaciones_user, 
                                                id_rol_user, 
                                                num_ventanilla) 
                                    VALUES (
                                        '$usuario', 
                                        '$passwordHash', 
                                        '$nombre', 
                                        '$dni', 
                                        '$genero', 
                                        '$puesto', 
                                        '$oficina', 
                                        '$observaciones', 
                                        '$rol', 
                                        '$ventanilla'
                                        )");

            if ($sql) {

                // Redirigir SOLO si insertó correctamente
                header("Location: lista_operadores.php?mensaje=registrado");
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