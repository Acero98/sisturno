<?php 
include "header.php";
?>

<div class="container-fluid">
    <div class="row">

        <!-- 🔹 COLUMNA TABLA -->
        <div class="col-6 p-4">
            <table class="table">
                <thead class="table-info">
                    <tr>
                        <th>N°</th>
                        <th>TICKET</th>
                        <th>SERVICIO</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../modelo/conexion.php";
                    $sql=$conexion->query("
                    select t.*, s.codigo_serv, s.nombre_serv
                    from tickets t
                    inner join servicios s on t.id_servicios = s.id_servicios
                    order by id_tickets desc 
                    limit 8");
                    $contador=1;
                    while($datos=$sql->fetch_object()){ ?>
                        <tr>
                            <th><?= $contador ?></th>
                            <td><?= $datos->codigo_serv." - ".$datos->numero_tk?></td>
                            <td><?= $datos->nombre_serv?></td>
                            <td><?= $datos->estado_tk ?></td>
                        </tr>
                    <?php
                    $contador++;
                    } ?>
                </tbody>
            </table>
        </div>
        
        <!-- 🔹 COLUMNA VIDEO -->
         <div class="col-6 d-flex justify-content-center align-items-center">
            <video id="videoPlayer" width="100%" autoplay muted></video>

            <script>
                const videos = [
                    "../videos/gotitasepsilo.mp4",
                    "../videos/mixepsilo.mp4",
                    "videos/video3.mp4"
                ];

                let index = 0;
                const player = document.getElementById("videoPlayer");

                function playNextVideo() {
                    player.src = videos[index];
                    player.play();
                    index = (index + 1) % videos.length;
                }

                player.addEventListener("ended", playNextVideo);

                playNextVideo();
            </script>
         </div>
        
    </div>
</div>

<?php
include "footer.php";
?>