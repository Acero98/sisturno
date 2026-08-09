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
        /* ==========================================================
       BASE
        ========================================================== */
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(135deg,
                    #1d4ed8 0%,
                    #2563eb 50%,
                    #1e40af 100%);
            font-family:
                "Segoe UI",
                Tahoma,
                Geneva,
                Verdana,
                sans-serif;
        }
        /* ==========================================================
       LOGIN
        ========================================================== */
        .login-card {
            width: 100%;
            max-width: 420px;
            border: 0;
            border-radius: 24px;
            background: #ffffff;
            box-shadow:
                0 20px 25px -5px rgba(15, 23, 42, .10),
                0 8px 10px -6px rgba(15, 23, 42, .10);
            overflow: hidden;
        }
        /* ==========================================================
       LOGO
    ========================================================== */
        .login-logo {
            max-width: 160px;
            max-height: 150px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        /* ==========================================================
       TÍTULOS
    ========================================================== */
        .login-title {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #0f172a;
        }
        .login-subtitle {
            color: #64748b;
            font-size: .95rem;
        }
        /* ==========================================================
       LABELS
    ========================================================== */
        .form-label {
            margin-bottom: .5rem;
            color: #334155;
            font-size: .875rem;
            font-weight: 600;
        }
        /* ==========================================================
       INPUTS
    ========================================================== */
        .input-group {
            border-radius: 12px;
            overflow: hidden;
        }
        .input-group-text {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .form-control {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: .8rem 1rem;
            color: #0f172a;
            font-size: .95rem;
            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background-color .2s ease;
        }
        .form-control:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow:
                0 0 0 3px rgba(59, 130, 246, .12);
        }
        /* Evita doble borde extraño */
        .input-group .form-control {
            border-left: 0;
        }
        .input-group .form-control:focus {
            border-left: 0;
        }
        /* ==========================================================
       BOTÓN LOGIN
    ========================================================== */
        .btn-login {
            width: 100%;
            padding: .85rem 1rem;
            border: 0;
            border-radius: 12px;
            background: #2563eb;
            color: #ffffff;
            font-size: .95rem;
            font-weight: 700;
            box-shadow:
                0 4px 6px -1px rgba(37, 99, 235, .20);
            transition:
                all .2s ease;
        }
        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow:
                0 8px 12px -4px rgba(37, 99, 235, .25);
            color: #ffffff;
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .btn-login:focus {
            box-shadow:
                0 0 0 4px rgba(59, 130, 246, .20);
        }
        /* ==========================================================
       FOOTER
    ========================================================== */
        .footer-text {
            margin-top: 1.25rem;
            color: #94a3b8;
            font-size: .75rem;
            line-height: 1.5;
            text-align: center;
        }
        /* ==========================================================
       RESPONSIVE
    ========================================================== */
        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }
            .login-card {
                border-radius: 20px;
            }
            .login-logo {
                max-width: 130px;
                max-height: 120px;
            }
            .login-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- =====================================================
             ENCABEZADO
        ====================================================== -->
        <div class="text-center px-4 px-md-5 pt-5 pb-4">
            <div class="mb-4">
                <img
                    src="<?= BASE_LOGO_LOGIN ?>"
                    alt="Logo"
                    class="login-logo">
            </div>
            <h1 class="login-title mb-2">
                Bienvenido
            </h1>
            <p class="login-subtitle mb-0">
                Sistema de Gestión de Turnos
            </p>
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