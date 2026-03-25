<?php
include "../../modelo/conexion.php";
include "../../control/auth.php";
include "../../control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

include "../../controlador/servicios/eliminar_servicio.php";
include "../../controlador/servicios/registrar_servicio.php";
include "../../controlador/servicios/modificar_servicio.php";
include "../header.php";
?>

<div class="container-fluid mt-4">       

    <!-- TABLA -->
    <div class="row mt-5">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalRegistro">
                            <i class="fa-solid fa-plus"></i>
                        </button>

                        <h5 class="mb-0">Lista de Servicios</h5>

                        <div class="col-md-4">
                            <form method="GET">
                                <div class="input-group">
                                    <input type="text" 
                                        name="buscar" 
                                        class="form-control" 
                                        placeholder="Buscar servicio..."
                                        value="<?= isset($_GET['buscar']) ? $_GET['buscar'] : '' ?>">

                                    <button class="btn btn-primary" type="submit">
                                        Buscar
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $registrosPorPagina = 10;

                                // Página actual
                                $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                if ($pagina < 1) $pagina = 1;

                                // Calcular inicio
                                $inicio = ($pagina - 1) * $registrosPorPagina;

                                //BUSCAR REGISTRO
                                $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
                                $where = "";

                                if (!empty($buscar)) {
                                    $buscar = $conexion->real_escape_string($buscar);
                                    $where = "WHERE nombre_serv LIKE '%$buscar%' 
                                            OR codigo_serv LIKE '%$buscar%'";
                                }

                                // Contar total registros
                                $totalRegistrosQuery = $conexion->query("
                                    SELECT COUNT(*) as total 
                                    FROM servicios s
                                    $where
                                ");

                                $totalRegistros = $totalRegistrosQuery->fetch_object()->total;

                                // Total páginas
                                $totalPaginas = ceil($totalRegistros / $registrosPorPagina);                           

                                
                                $sql = $conexion->query("SELECT * FROM servicios $where 
                                                        LIMIT $inicio, $registrosPorPagina");

                                while($datos=$sql->fetch_object()){ ?>

                                    <tr>
                                        <td><?= $datos->id_servicios ?></td>
                                        <td><?= $datos->nombre_serv ?></td>
                                        <td><?= $datos->codigo_serv ?></td>
                                        <td>
                                            <span class="badge <?= $datos->estado_serv == 1 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $datos->estado_serv == 1 ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar<?= $datos->id_servicios ?>">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <?php if($datos->estado_serv == 1): ?>

                                                <a href="#"
                                                class="btn btn-danger btn-sm btnDesactivar"
                                                data-id="<?= $datos->id_servicios ?>">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </a>

                                            <?php else: ?>

                                                <a href="#"
                                                class="btn btn-success btn-sm btnActivar"
                                                data-id="<?= $datos->id_servicios ?>">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </a>

                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDITAR -->
                                    <div class="modal fade" id="modalEditar<?= $datos->id_servicios ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">Editar Servicio</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <form method="POST" class="formEditarServicio">

                                                    <input type="hidden" name="id" value="<?= $datos->id_servicios ?>">
                                                    <!-- <input type="hidden" name="btnmodificarservicios" value="ok"> -->

                                                    <div class="mb-3">
                                                        <label class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" name="nombre"
                                                            value="<?= $datos->nombre_serv ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Código</label>
                                                        <input type="text" class="form-control" name="codigo"
                                                            value="<?= $datos->codigo_serv ?>" required>
                                                    </div>                                              

                                                    <button type="submit" class="btn btn-warning">
                                                        Guardar Cambios
                                                    </button>

                                                </form>

                                            </div>

                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                            </tbody>
                        </table>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">

                                <!-- Botón anterior -->
                                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?pagina=<?= $pagina-1 ?>&buscar=<?= urlencode($buscar) ?>">Anterior</a>
                                </li>

                                <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Botón siguiente -->
                                <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?pagina=<?= $pagina+1 ?>&buscar=<?= urlencode($buscar) ?>">Siguiente</a>
                                </li>

                            </ul>
                        </nav>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- BOTÓN FLOTANTE -->
<button type="button"
        class="btn btn-primary rounded-circle shadow"
        data-bs-toggle="modal"
        data-bs-target="#modalRegistro"
        style="position: fixed; bottom: 30px; right: 30px; width:60px; height:60px;">
    +
</button>

<!-- MODAL -->
<div class="modal fade" id="modalRegistro" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Registrar Servicio</h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form method="POST" class="formRegistrarServicio">
            <input type="hidden" name="btnregistrarServicio" value="ok">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Código</label>
                <input type="text" class="form-control" name="codigo" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado" required>
                    <option value="">Seleccione estado</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

          <div class="d-grid">
            <button type="button"
                    class="btn btn-primary btnConfirmarRegistro">
                Registrar
            </button>
          </div>

        </form>

      </div>

    </div>
  </div>
</div>

<script src="<?= BASE_URL ?>public/js/servicios.js"></script>

<?php
include "../footer.php";
?>