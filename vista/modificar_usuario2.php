<?php 
require_once __DIR__ . "/../modelo/conexion.php";

$id = $_GET["id"];
$sql = $conexion->query(" SELECT * FROM usuarios WHERE id_usuario=$id");
include "header.php";
?>


<form class="col-4 p-4 m-auto shadow" method="POST">
    <h3 class="text-center text-secondary">Modificar Usuario</h3>

    <input type="hidden" name="id" value="<?= $_GET["id"] ?>">

    <?php 
    include_once __DIR__ . "/../controlador/modificar_usuario.php";

    while($datos = $sql->fetch_object()){ ?>
    
    <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" class="form-control" name="usuario" value="<?= $datos->usuario_user ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="nombre" value="<?= $datos->nombre_user ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Nueva Contraseña</label>
        <input type="password" class="form-control" name="password">
        <small class="text-muted">Dejar vacío si no desea cambiarla</small>
    </div>

    <?php 
    };
    ?>

    <button type="submit" class="btn btn-primary w-100" name="btnmodificar" value="ok">
        Modificar
    </button>
</form>

<?php
include "footer.php";
?>