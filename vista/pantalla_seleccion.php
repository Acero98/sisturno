<?php 
include "header.php";

?>

<div class="container mt-5">
    <div class="row">

        <?php 
            include "../modelo/conexion.php";
            $sql=$conexion->query("SELECT * FROM servicios WHERE estado_serv=1");
            while($datos=$sql->fetch_object()) { ?>

            <div class="col-6 mb-3">
                <form method="POST" action="../controlador/generar_ticket.php">
                    <input type="hidden" name="id_servicios" value="<?= $datos->id_servicios ?>">
                    
                    <button type="submit" name="generar_turno" class="btn btn-primary w-100 py-4">
                        <?php echo $datos->nombre_serv ?>
                    </button>
                </form>
            </div>

        <?php } ?>

    </div>
</div>

<?php if(isset($_GET['ticket'])) { ?>
    <div class="alert alert-success text-center">
        Ticket generado: <strong><?= $_GET['ticket'] ?></strong>
    </div>
<?php } ?>

<?php
include "footer.php";
?>
