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

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0d6efd 0%, #2563eb 50%, #1d4ed8 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            border: none;
            border-radius: 28px;
            background: #ffffff;
            box-shadow:
                0 25px 50px rgba(15, 23, 42, 0.20),
                0 10px 20px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            animation: fadeInUp 0.7s ease;
        }

        .login-header {
            text-align: center;
            padding: 2.5rem 2rem 1.5rem;
        }

        .login-icon {
            text-align: center;
            margin-bottom: 1rem;
        }

        .login-logo {
            max-width: 180px;
            max-height: 180px;
            width: auto;
            height: auto;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 0;
        }

        .login-body {
            padding: 0 2.5rem 2.5rem;
        }

        .form-label {
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background: #f8fafc;
            border: 1px solid #dbe2ea;
            color: #0d6efd;
            font-size: 1.1rem;
        }

        .form-control {
            border: 1px solid #dbe2ea;
            padding: 0.85rem 1rem;
            font-size: 1rem;
            border-radius: 0 12px 12px 0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .input-group {
            border-radius: 12px;
            overflow: hidden;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            font-size: 1.05rem;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #0d6efd, #2563eb);
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.25);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.35);
        }

        .footer-text {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-card {
                margin: 1rem;
                border-radius: 24px;
            }

            .login-header {
                padding: 2rem 1.5rem 1rem;
            }

            .login-body {
                padding: 0 1.5rem 2rem;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .login-logo {
                max-width: 140px;
                max-height: 140px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- Encabezado -->
        <div class="login-header">
            <div class="login-icon">
                <img src="<?= BASE_LOGO_LOGIN ?>" alt="Logo" class="login-logo">
            </div>

            <h1 class="login-title">Bienvenido</h1>
            <p class="login-subtitle">Sistema de Gestión de Turnos</p>
        </div>

        <!-- Formulario -->
        <div class="login-body">
            <form method="POST">

                <!-- Usuario -->
                <div class="mb-4">
                    <label class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text"
                            name="usuario"
                            class="form-control"
                            placeholder="Ingrese su usuario"
                            required
                            autofocus>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label class="form-label">Contraseña</label>

                    <div class="input-group">
                        <!-- Icono izquierdo -->
                        <span class="input-group-text">
                            <i class="fa-solid fa-lock"></i>
                        </span>

                        <!-- Campo password -->
                        <input type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Ingrese su contraseña"
                            required>

                        <!-- Botón mostrar/ocultar -->
                        <button type="button"
                            class="input-group-text bg-white"
                            id="togglePassword"
                            style="cursor: pointer;">
                            <i class="fa-solid fa-eye" id="iconPassword"></i>
                        </button>
                    </div>
                </div>

                <!-- Botón -->
                <button type="submit" name="login" class="btn btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Ingresar al Sistema
                </button>

                <div class="footer-text">
                    © <?= date('Y') ?> Sistema de Turnos | Derechos reservados por Roman Acero
                </div>

            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="assets/js/sweetalert2.all.min.js"></script>

    <!-- Tus alertas -->
    <script src="public/js/login.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById("password");
            const toggleButton = document.getElementById("togglePassword");
            const icon = document.getElementById("iconPassword");

            toggleButton.addEventListener("click", function() {
                const isPassword = passwordInput.type === "password";

                // Cambiar tipo de input
                passwordInput.type = isPassword ? "text" : "password";

                // Cambiar ícono
                if (isPassword) {
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });
        });
    </script>

</body>

</html>