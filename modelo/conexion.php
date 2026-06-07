<?php
/*
|--------------------------------------------------------------------------
| CONEXIÓN A LA BASE DE DATOS
|--------------------------------------------------------------------------
| Si ocurre un error de conexión, se muestra un mensaje amigable
| indicando al usuario que debe comunicarse con el administrador.
|--------------------------------------------------------------------------
*/

// Configurar zona horaria
date_default_timezone_set("America/Lima");

// Crear conexión
//$conexion = new mysqli("localhost", "root", "QvcPeru23$", "siscolas");
$conexion = new mysqli("localhost", "root", "Supot#0326", "siscolas");

// Verificar si hubo error de conexión
if ($conexion->connect_error) {
    die('
        <div style="
            max-width: 700px;
            margin: 80px auto;
            padding: 40px;
            background: #ffffff;
            border-left: 6px solid #dc3545;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            font-family: Segoe UI, Arial, sans-serif;
            text-align: center;
        ">
            <div style="font-size: 60px; margin-bottom: 20px;">⚠️</div>

            <h2 style="
                color: #dc3545;
                margin-bottom: 20px;
                font-size: 30px;
                font-weight: 700;
            ">
                Error de conexión a la base de datos
            </h2>

            <p style="
                font-size: 20px;
                color: #495057;
                line-height: 1.6;
                margin-bottom: 15px;
            ">
                No fue posible establecer comunicación con la base de datos.
            </p>

            <p style="
                font-size: 20px;
                color: #495057;
                line-height: 1.6;
                font-weight: 600;
            ">
                Por favor, comuníquese con el administrador del sistema.
            </p>

            <hr style="
                margin: 30px 0;
                border: none;
                border-top: 1px solid #dee2e6;
            ">

            <p style="
                font-size: 14px;
                color: #6c757d;
                margin: 0;
            ">
                Sistema de Gestión de Colas
            </p>
        </div>
    ');
} else {
    //echo "Conexión exitosa.";
}

// Configurar codificación UTF-8
$conexion->set_charset("utf8");

// Fecha y hora actual
$fechahora_actual = date("Y-m-d H:i:s");


/*
$conexion=new mysqli("localhost","root","QvcPeru23$","siscolas");
$conexion->set_charset("utf8");

date_default_timezone_set("America/Lima"); // Perú
$fechahora_actual = date("Y-m-d H:i:s");*/
