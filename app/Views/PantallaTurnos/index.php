<?php require __DIR__ . '/../../../vista/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>public/css/pantalla-turnos-mvc.css">

<main class="pantalla-turnos">
    <div class="container-fluid py-3 py-xl-4">
        <div class="turnos-layout">
            <section class="turnos-column">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">Turnos en Espera y Atención</div>
                    <div class="card-body p-0 card-body-scroll">
                        <div id="contenedorTurnos"><?php require __DIR__ . '/components/tabla_turnos.php'; ?></div>
                    </div>
                </div>
            </section>
            <aside class="media-column">
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-primary mb-2"><i class="fas fa-ticket-alt me-2"></i>SISTEMA DE TURNOS</h1>
                    <div class="fecha-hora">
                        <div id="horaActual" class="hora-grande"></div>
                        <div id="fechaActual" class="fecha-pequena"></div>
                    </div>
                </div>
                <div class="video-container"><video id="videoPlayer" autoplay controls playsinline></video></div>
            </aside>
            <audio id="audioNotificacion" preload="auto">
                <source src="<?= URL_AUDIO ?>notificacion.mp3" type="audio/mpeg">
            </audio>
        </div>
    </div>
</main>

<script>
    window.PANTALLA_TURNOS_CONFIG = {
        baseUrl: <?= json_encode(BASE_URL) ?>,
        videos: [<?= json_encode(URL_VIDEO . 'video-1.mp4') ?>]
    };
</script>
<script src="<?= BASE_URL ?>public/js/socket_config.js"></script>
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>
<script src="<?= BASE_URL ?>public/js/pantalla-turnos-mvc.js"></script>
<?php require __DIR__ . '/../../../vista/footer.php'; ?>
