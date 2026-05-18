<?php

session_start();
include "../../config.php";
require "../../modelo/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

include "../../controlador/atencion/obtener_ticket.php";
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
<script src="<?= BASE_URL ?>assets/js/socket.io.min.js"></script>

<script>
    const SOCKET_URL = "http://192.168.100.120:3000";
</script>

<script src="<?= BASE_URL ?>public/js/atencionCliente.js"></script>

<?php

include "../footer.php";

?>