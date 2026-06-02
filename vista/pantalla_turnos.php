<?php

require_once __DIR__ . "/../control/auth.php";
require_once __DIR__ . "/../control/permisos.php";

permitirSolo(["Super Admin", "Admin", "Operador", "Monitor", "Turnos"]);

include "header.php";
//include "../modelo/conexion.php";
?>

<link rel="stylesheet" href="<?= BASE_URL ?>public/css/pantalla_turnos.css">

<div class="container-fluid py-4">

    <!-- ENCABEZADO -->
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary mb-2">
            <i class="fas fa-ticket-alt me-2"></i>
            SISTEMA DE TURNOS
        </h1>
        <div class="fecha-hora" id="fechaHora"></div>
    </div>

    <div class="row g-4">

        <!-- COLUMNA IZQUIERDA: TABLA DE TURNOS -->
        <div class="col-lg-6">
            <div class="card card-custom h-100">
                <div class="card-header card-header-custom">
                    Turnos en Espera y Atención
                </div>

                <div class="card-body p-0 card-body-scroll">
                    <div id="contenedorTurnos">
                        <?php include "tabla_turnos.php"; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: VIDEO -->
        <div class="col-lg-6">
            <div class="video-container h-100">
                <video
                    id="videoPlayer"
                    autoplay
                    muted
                    controls
                    playsinline></video>
            </div>
        </div>

    </div>
</div>

<!-- 
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script> -->

<!-- Socket.IO --> 
<script src="<?= BASE_URL ?>public/js/socket_config.js"></script>
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>

<!-- PARA MOSTRAR LOS VIDEOS -->
<script>
    /* ==========================================================
       LISTA DE VIDEOS
       ========================================================== */
    const videos = [
        "<?= BASE_URL ?>public/videos/video-1.mp4"
    ];

    const player = document.getElementById("videoPlayer");

    /* ==========================================================
       CARGAR ESTADO GUARDADO EN localStorage
       ==========================================================
       Se guarda:
       - videoIndex     -> índice del video actual
       - videoTime      -> segundo exacto donde quedó el video
    ========================================================== */
    let index = parseInt(localStorage.getItem("videoIndex")) || 0;
    let savedTime = parseFloat(localStorage.getItem("videoTime")) || 0;

    /* ==========================================================
       REPRODUCIR VIDEO ACTUAL
       ========================================================== */
    function playCurrentVideo() {
        player.src = videos[index];

        // Cuando el video cargue, posicionarlo en el segundo guardado
        player.addEventListener("loadedmetadata", function restoreTime() {
            // Solo restaurar si el tiempo es válido
            if (savedTime > 0 && savedTime < player.duration) {
                player.currentTime = savedTime;
            }

            // Eliminar el listener para evitar ejecuciones repetidas
            player.removeEventListener("loadedmetadata", restoreTime);

            // Reproducir
            player.play().catch(error => {
                console.error("Error al reproducir el video:", error);
            });
        });
    }

    /* ==========================================================
       GUARDAR POSICIÓN DEL VIDEO CADA SEGUNDO
       ========================================================== */
    setInterval(() => {
        if (!player.paused && !isNaN(player.currentTime)) {
            localStorage.setItem("videoIndex", index);
            localStorage.setItem("videoTime", player.currentTime);
        }
    }, 1000);

    /* ==========================================================
       CUANDO TERMINA UN VIDEO
       ========================================================== */
    player.addEventListener("ended", () => {
        // Pasar al siguiente video
        index = (index + 1) % videos.length;

        // Reiniciar el tiempo
        savedTime = 0;

        // Guardar nuevo estado
        localStorage.setItem("videoIndex", index);
        localStorage.setItem("videoTime", 0);

        // Reproducir siguiente video
        playCurrentVideo();
    });

    /* ==========================================================
       SI UN VIDEO FALLA, CONTINUAR CON EL SIGUIENTE
       ========================================================== */
    player.addEventListener("error", () => {
        console.warn("No se pudo cargar:", player.src);

        index = (index + 1) % videos.length;
        savedTime = 0;

        localStorage.setItem("videoIndex", index);
        localStorage.setItem("videoTime", 0);

        playCurrentVideo();
    });

    /* ==========================================================
       INICIAR REPRODUCCIÓN
       ========================================================== */
    playCurrentVideo();

    /* ==========================================================
       MOSTRAR FECHA Y HORA
       ========================================================== */
    function actualizarFechaHora() {
        const ahora = new Date();

        const opciones = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };

        document.getElementById('fechaHora').textContent =
            ahora.toLocaleDateString('es-PE', opciones);
    }

    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);

    /* ==========================================================
    WEBSOCKET - ESCUCHAR CAMBIOS EN TIEMPO REAL
    ========================================================== */
    const socket = io(SOCKET_URL);

    socket.on("connect", function() {
        console.log("Conectado al WebSocket:", socket.id);
    });

    /*
    |--------------------------------------------------------------------------
    | FUNCIÓN PARA ACTUALIZAR SOLO LA TABLA DE TURNOS
    |--------------------------------------------------------------------------
    | Esta función obtiene el contenido HTML de un archivo PHP (por ejemplo:
    | tabla_turnos.php) y reemplaza únicamente el contenido del contenedor
    | con id="contenedorTurnos".
    |--------------------------------------------------------------------------
    */
    function actualizarTablaTurnos() {
        fetch("tabla_turnos.php")
            .then(response => response.text())
            .then(html => {
                document.getElementById("contenedorTurnos").innerHTML = html;
            })
            .catch(error => {
                console.error("Error al actualizar la tabla:", error);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | CUANDO EL SERVIDOR ENVÍA EL EVENTO "actualizar_pantalla"
    |--------------------------------------------------------------------------
    | Ya NO usamos location.reload().
    | Solo actualizamos la tabla de turnos.
    |--------------------------------------------------------------------------
    */
    socket.on("actualizar_pantalla", function(data) {
        console.log("Evento recibido:", data);
        actualizarTablaTurnos();
    });
</script>

<?php
include "footer.php";
?>