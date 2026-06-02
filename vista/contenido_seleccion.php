<?php
require_once __DIR__ . "/../modelo/conexion.php";

require_once __DIR__ . "/../control/auth.php";
require_once __DIR__ . "/../control/permisos.php";

permitirSolo(["Super Admin", "Admin", "Monitor"]);
?>

<!--<div class="container-fluid bg-light min-vh-100 py-5"> -->
<div class="container-fluid bg-light py-4">

    <!-- TÍTULO PRINCIPAL -->
    <div class="text-center mb-5">
        <!-- Ícono 
        <div class="d-inline-flex justify-content-center align-items-center
                    bg-primary text-white rounded-circle shadow mb-4"
             style="width: 100px; height: 100px;">
            <i class="fas fa-ticket-alt fa-3x"></i>
        </div> -->

        <!-- Título 
        <h1 class="display-4 fw-bold text-primary mb-3"> -->
        <h1 class="fw-bold text-primary mb-2 titulo-principal">
            BIENVENIDO
        </h1>

        <!-- Subtítulo 
        <p class="fs-4 text-secondary mb-0">-->
        <p class="text-secondary mb-0 subtitulo-principal">
            Presione el servicio que desea solicitar para generar su ticket.
        </p>
    </div>

    <!-- LISTADO DE SERVICIOS -->
    <div class="container">
        <div class="row g-4 justify-content-center">
        <!--<div class="row g-3">-->

            <?php
            //include "../modelo/conexion.php";

            $sql = $conexion->query("SELECT * FROM servicios WHERE estado_serv = 1 ORDER BY nombre_serv ASC");
            //include "../controlador/atencion/notificar_socket.php";

            while ($datos = $sql->fetch_object()) {
            ?>

                <!-- Manteniendo la estructura base 
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3"> -->
                <div class="col-6 col-md-4 col-xl-3">

                    <form method="POST"
                        action="../controlador/generar_ticket.php"
                        class="form-generar-ticket h-100">

                        <input type="hidden"
                            name="id_servicios"
                            value="<?= $datos->id_servicios ?>">

                        <!-- <button type="submit"
                            name="generar_turno"
                            class="btn btn-primary w-100 h-100 py-5 px-4 border-0 shadow-lg rounded-4"> -->
                        <button type="submit"
                            name="generar_turno"
                            class="btn btn-primary w-100 h-100 p-3 border-0 shadow rounded-4 boton-servicio">

                            <!-- Ícono 
                            <div class="mb-4">
                                <i class="fas fa-concierge-bell text-white icono-servicio"></i>
                            </div>-->

                            <!-- Nombre del servicio -->
                            <div class="fw-bold text-uppercase"
                                style="font-size: 1.7rem; line-height: 1.3;">
                            <!-- <div class="fw-bold text-uppercase nombre-servicio">-->
                                <?= strtoupper($datos->nombre_serv) ?>
                            </div>

                            <!-- Texto secundario
                            <div class="mt-3 text-white-50 fw-semibold"
                                style="font-size: 1rem;">  
                            <div class="mt-2 text-white-50 fw-semibold texto-secundario">
                                Toque aquí para obtener su turno
                            </div>-->

                        </button>
                    </form>

                </div>

            <?php } ?>

        </div>
    </div>
</div>