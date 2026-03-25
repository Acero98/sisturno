<?php
session_start();
include "modelo/conexion.php";

if(isset($_POST['login'])){

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = $conexion->query("SELECT u.*, r.nombre_rol 
                             FROM usuarios u
                             INNER JOIN roles r ON u.id_rol_user = r.id_rol
                             WHERE usuario_user='$usuario'");

    if($datos = $sql->fetch_object()){

        if($datos->estado_user == 0){
            header("Location: login.php?error=desactivado");
            exit();
        }

        if(password_verify($password, $datos->password_user)){

            $_SESSION['usuario'] = $datos->usuario_user;
            $_SESSION['id_usuario'] = $datos->id_usuario;
            $_SESSION['rol'] = $datos->nombre_rol;

            header("Location: index.php");
            exit();

        } else {
            header("Location: login.php?error=password");
            exit();
        }

    } else {
        header("Location: login.php?error=noexiste");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- CSS only-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c40e82f1b2.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="width: 400px;">

            <h3 class="text-center mb-4">Iniciar Sesión</h3>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    Ingresar
                </button>

            </form>

        </div>
    </div>

<!-- JS only-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/login.js"></script>
</body>
</html>