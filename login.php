<?php
session_start();
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/modelo/conexion.php";
if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $sql = $conexion->query("SELECT u.*, r.nombre_rol 
                            FROM usuarios u
                            INNER JOIN roles r ON u.id_rol_user = r.id_rol
                            WHERE usuario_user='$usuario'");
    if ($datos = $sql->fetch_object()) {
        if ($datos->estado_user == 0) {
            header("Location: login.php?error=desactivado");
            exit();
        }
        if (password_verify($password, $datos->password_user)) {
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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Sistema de Turnos</title>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_FAVICON ?>">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="public/css/login.css">
    
</head>

<body>
    <div class="login-card">
        <!-- =====================================================
             ENCABEZADO
        ====================================================== -->
        <div class="text-center px-4 px-md-5 pt-5">
            <div class="mb-4">
                <img
                    src="<?= BASE_LOGO_LOGIN ?>"
                    alt="Logo"
                    class="login-logo">
            </div>
            <!--
            <h1 class="login-title mb-2">
                Bienvenido
            </h1> 
            <p class="login-subtitle mb-0">
                EMPRESA PRESTADORA DE SERVICIOS DE SANEAMIENTO DE AGUA POTABLE Y ALCANTARILLADO DE ILO S.A.
            </p>-->
        </div>
        <!-- =====================================================
             FORMULARIO
        ====================================================== -->
        <div class="px-4 px-md-5 pb-5">
            <form method="POST">
                <!-- Usuario -->
                <div class="mb-4">
                    <label
                        for="usuario"
                        class="form-label">
                        Usuario
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input
                            type="text"
                            name="usuario"
                            id="usuario"
                            class="form-control"
                            placeholder="Ingrese su usuario"
                            required
                            autofocus>
                    </div>
                </div>
                <!-- Contraseña -->
                <div class="mb-4">
                    <label
                        for="password"
                        class="form-label">
                        Contraseña
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Ingrese su contraseña"
                            required>
                        <button
                            type="button"
                            class="input-group-text"
                            id="togglePassword"
                            style="cursor: pointer;">
                            <i
                                class="fa-solid fa-eye"
                                id="iconPassword"></i>
                        </button>
                    </div>
                </div>
                <!-- Botón -->
                <button
                    type="submit"
                    name="login"
                    class="btn btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Ingresar al Sistema
                </button>
                <!-- Footer -->
                <div class="footer-text">
                    © <?= date('Y') ?>
                    Sistema de Turnos
                    <span class="mx-1">·</span>
                    Derechos reservados por Roman Acero
                </div>
            </form>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert -->
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <!-- Alertas -->
    <script src="public/js/login.js"></script>
    <!-- Mostrar / ocultar contraseña -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput =
                document.getElementById("password");
            const toggleButton =
                document.getElementById("togglePassword");
            const icon =
                document.getElementById("iconPassword");
            toggleButton.addEventListener("click", function() {
                const isPassword =
                    passwordInput.type === "password";
                passwordInput.type =
                    isPassword ?
                    "text" :
                    "password";
                icon.classList.toggle(
                    "fa-eye",
                    !isPassword
                );
                icon.classList.toggle(
                    "fa-eye-slash",
                    isPassword
                );
            });
        });
    </script>
</body>

</html>