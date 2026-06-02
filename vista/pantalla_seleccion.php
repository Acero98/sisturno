<?php
require_once __DIR__ . "/../modelo/conexion.php";
//include "../config.php";

require_once __DIR__ . "/../control/auth.php";
require_once __DIR__ . "/../control/permisos.php";

permitirSolo(["Super Admin", "Admin", "Monitor", "Turnos"]);

include "header.php";
?>

<!--<link rel="stylesheet" href="<= BASE_URL ?>public/css/pantalla_seleccion.css">-->
<!-- CONTENEDOR QUE SE ACTUALIZARÁ AUTOMÁTICAMENTE -->
<div id="contenedorSeleccion">
    <?php include "contenido_seleccion.php"; ?>
</div>

<!-- Librería Socket.IO 
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>-->

<!-- Socket.IO -->
<script src="<?= BASE_URL ?>public/js/socket_config.js"></script>
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>

<!-- URL del controlador utilizada por JavaScript -->
<script>
    const URL_GENERAR_TICKET = "<?= BASE_URL ?>controlador/generar_ticket.php";
    //const SOCKET_URL = "http://192.168.100.120:3000";
    // Ruta absoluta del archivo que contiene los servicios
    //const URL_CONTENIDO_SELECCION = "<= BASE_URL ?>vista/contenido_seleccion.php";
</script>

<!-- JavaScript para generar ticket y mostrar modal -->
<script src="<?= BASE_URL ?>public/js/generar_ticket.js"></script>

<?php
include "footer.php";
?>