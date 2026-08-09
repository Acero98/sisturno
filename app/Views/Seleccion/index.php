<?php require __DIR__ . '/../../../vista/header.php'; ?>

<div id="contenedorSeleccion">
    <?php require __DIR__ . '/components/contenido.php'; ?>
</div>

<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script src="<?= BASE_URL ?>public/js/socket_config.js"></script>
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>
<script src="<?= BASE_URL ?>public/js/seleccion-mvc.js"></script>
<?php require __DIR__ . '/../../../vista/footer.php'; ?>
