<?php
include "header.php";
//include "../modelo/conexion.php";
?>

<style>
    body {
        background: #f4f6f9;
    }

    /* Tarjetas principales */
    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    /* Encabezados */
    .card-header-custom {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
        font-size: 1.4rem;
        font-weight: 700;
        padding: 1rem 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Tabla */
    .table-turnos {
        margin-bottom: 0;
    }

    .table-turnos thead th {
        font-size: 1rem;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 1rem;
    }

    .table-turnos tbody td,
    .table-turnos tbody th {
        font-size: 1.1rem;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }

    /* Ticket destacado */
    .ticket-code {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0d6efd;
        letter-spacing: 1px;
    }

    /* Video */
    .video-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        background: #000;

        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 620px;
    }

    #videoPlayer {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        display: block;
    }

    /* Badge grande */
    .badge-estado {
        font-size: 0.95rem;
        padding: 0.55rem 0.85rem;
        border-radius: 50rem;
        font-weight: 700;
    }

    /* Altura mínima */
    .card-body-scroll {
        min-height: 620px;
    }

    /* Reloj opcional */
    .fecha-hora {
        font-size: 1rem;
        font-weight: 600;
        color: #6c757d;
    }
</style>

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
                <video id="videoPlayer" autoplay muted></video>
            </div>
        </div>

    </div>
</div>

<!-- 
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script> -->

<!-- Socket.IO -->
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>

<script>
    /* ==========================================================
       LISTA DE VIDEOS
       ========================================================== */
    const videos = [
        "<?= BASE_URL ?>public/videos/video-1.mp4",
        "<?= BASE_URL ?>public/videos/video-2.mp4",
        "<?= BASE_URL ?>public/videos/video-3.mp4"
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
    const socket = io("http://192.168.100.120:3000");

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