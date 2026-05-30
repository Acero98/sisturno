<?php

session_start();
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../modelo/conexion.php";

/*
if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}*/

require_once __DIR__ . "/../../control/auth.php";
require_once __DIR__ . "/../../control/permisos.php";

permitirSolo(["Super Admin", "Admin", "Operador"]);

include_once __DIR__ . "/../../controlador/atencion/obtener_ticket.php";
include "../header.php";

?>

<style>
    body {
        background-color: #f4f6f9;
    }

    .card-custom {
        border-radius: 12px;
    }

    .empty-state img {
        max-width: 250px;
    }
</style>



<div id="contenedorAtencion">
    <?php include "contenido_atencion.php"; ?>
</div>

<!-- 
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js">
    //const SOCKET_URL = "http://localhost:3000";
</script> -->

<!-- Socket.IO -->
<script src="<?= BASE_URL ?>public/js/socket_config.js"></script>
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>

<script src="<?= BASE_URL ?>public/js/atencionCliente.js"></script>

<?php

include "../footer.php";

?>