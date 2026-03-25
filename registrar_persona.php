<?php

session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include "vista/header.php";
?>

    <script>
        function eliminar(){
            var respuesta=confirm("Estas seguro que deseas eliminar?");
            return respuesta
        }
    </script>
<h1 class="text-center p-3">Hola Mundo</h1>
<?php 
include "modelo/conexion.php";
include "controlador/eliminar_persona.php";

?>
<div class="container-fluid row">
    <form class="col-4" method="POST">
        <h3 class="text-center text-secondary">Registro de personas</h3>
        <?php 
        
        include "controlador/registro_persona.php";
        ?>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Nombre de la persona</label>
            <input type="text" class="form-control" name="nombre">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Apellido de la persona</label>
            <input type="text" class="form-control" name="apellido">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">DNI de la persona</label>
            <input type="text" class="form-control" name="dni">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Fecha de la persona</label>
            <input type="date" class="form-control" name="fecha">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo de la persona</label>
            <input type="text" class="form-control" name="correo">
        </div>
        
        <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">Registrar</button>
    </form>
    <div class="col-8 p-4">
        <table class="table">
            <thead class="table-info">
                <tr>
                <th scope="col">ID</th>
                <th scope="col">NOMBRES</th>
                <th scope="col">APELLIDOS</th>
                <th scope="col">DNI</th>
                <th scope="col">FECHA DE NAC.</th>
                <th scope="col">CORREO</th>
                <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "modelo/conexion.php";
                $sql=$conexion->query("select * from persona");
                while($datos=$sql->fetch_object()){ ?>
                    <tr>
                        <th><?= $datos->id_persona ?></th>
                        <td><?= $datos->nombre ?></td>
                        <td><?= $datos->apellido ?></td>
                        <td><?= $datos->dni ?></td>
                        <td><?= $datos->fecha_nac ?></td>
                        <td><?= $datos->correo ?></td>
                        <td>
                            <a href="modificar_persona.php?id=<?= $datos->id_persona ?>" class="btn btn-small btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a onclick="return eliminar()" href="index.php?id=<?= $datos->id_persona ?>" class="btn btn-small btn-danger"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                
            </tbody>
        </table>
    </div>
</div>
    
<?php
include "vista/footer.php";
?>