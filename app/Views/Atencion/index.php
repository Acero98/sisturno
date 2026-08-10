<?php include __DIR__ . '/../Layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>public/css/atencion_cliente.css">
<style>
    body {
        background-color: #f4f6f9
    }

    .card-custom {
        border-radius: 12px
    }
</style>
<div id="contenedorAtencion"><?php require __DIR__ . '/components/contenido.php'; ?></div>
<script>
    window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= BASE_URL ?>public/js/socket_config.js"></script>
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>
<script src="<?= BASE_URL ?>public/js/atencion-cliente-mvc.js"></script>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>