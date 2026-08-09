<?php

include __DIR__ . '/../../../vista/header.php';
?>
<br>
<div class="container-fluid">

    <?php require __DIR__ . '/components/bienvenida.php'; ?>
    <br>
    <?php require __DIR__ . '/components/metricas.php'; ?>

    <div class="row g-4">

        <?php require __DIR__ . '/components/horas-pico.php'; ?>

        <?php require __DIR__ . '/components/servicios.php'; ?>

        <?php require __DIR__ . '/components/ranking.php'; ?>

        <?php require __DIR__ . '/components/tendencia.php'; ?>

    </div>
</div>
<script src="<?= BASE_URL ?>assets/plugins/js/apexcharts.min.js"></script>
<script src="<?= BASE_URL ?>public/js/dashboard.js"></script>

<script>
    window.dashboardData = {

        horasLabels: <?= json_encode($horasLabels) ?>,
        horasData: <?= json_encode($horasData) ?>,

        serviciosLabels: <?= json_encode($serviciosLabels) ?>,
        serviciosData: <?= json_encode($serviciosData) ?>,

        tendenciaLabels: <?= json_encode($tendenciaLabels) ?>,
        tendenciaData: <?= json_encode($tendenciaData) ?>

    };
</script>

<?php
include __DIR__ . '/../../../vista/footer.php';
?>