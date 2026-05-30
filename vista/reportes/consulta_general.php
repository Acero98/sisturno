<?php

//require_once __DIR__ . "/../../modelo/conexion.php";
require_once __DIR__ . "/../../modelo/conexion.php";

require_once __DIR__ . "/../../control/auth.php";
require_once __DIR__ . "/../../control/permisos.php";

permitirSolo(["Super Admin", "Admin"]);

include_once __DIR__ . "/../../controlador/reportes/consulta_general.php";

include "../header.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>public/css/consultaGeneral.css">

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="page-header-card mb-2 py-2">
        <div class="row align-items-center">

            <div class="col-lg-9">

                <h5 class="mb-1">
                    <i class="fa-solid fa-magnifying-glass-chart me-2"></i>
                    Consulta General de Tickets
                </h5>

                <p class="mb-0">
                    Búsqueda y filtrado de tickets por fecha, estado, servicio y operador.
                </p>

            </div>

            <div class="col-lg-3 text-end d-none d-lg-block">
                <i class="fa-solid fa-table-list"
                    style="font-size: 2.6rem; opacity: 0.10;"></i>
            </div>

        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 dashboard-card">
        <div class="card-body p-3">

            <!-- HEADER COMPACTO -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h6 class="fw-bold mb-0">
                    <i class="fa-solid fa-filter text-primary me-1"></i>
                    Filtros
                </h6>
            </div>

            <!-- FORM COMPACTO -->
            <form method="GET">
                <div class="row g-2 align-items-center">

                    <div class="col-md-3">
                        <input type="date" name="inicio" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($fechaInicio) ?>">
                    </div>

                    <div class="col-md-3">
                        <input type="date" name="fin" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($fechaFin) ?>">
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" name="tipo" value="personalizado"
                            class="btn btn-sm btn-success w-100">
                            Filtrar
                        </button>

                        <a href="?tipo=hoy"
                            class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>

                    <!-- EXPORTACIÓN INLINE -->
                    <div class="col-md-3 d-flex gap-1 justify-content-end">
                        <a id="btnExcel" class="btn btn-sm btn-success">
                            <i class="fa-solid fa-file-excel"></i>
                        </a>

                        <a id="btnPDF" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>

                        <button onclick="window.print()" class="btn btn-sm btn-dark">
                            <i class="fa-solid fa-print"></i>
                        </button>
                    </div>

                </div>
            </form>

            <!-- INFO MINIMIZADA -->
            <div class="mt-2 small text-muted">
                <i class="fa-solid fa-circle-info me-1"></i>
                <?= ucfirst($tipo) ?>:
                <?= date('d/m/Y', strtotime($fechaInicio)) ?> -
                <?= date('d/m/Y', strtotime($fechaFin)) ?>
            </div>

            <input type="hidden" id="tipoFiltro" value="<?= $tipo ?>">
        </div>
    </div>

    <!--
    <div class="card shadow-sm border-0 mb-4 dashboard-card">

        <div class="card-body p-4">

             //ENCABEZADO
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">

        <div>
            <h5 class="fw-bold mb-1">
                <i class="fa-solid fa-filter text-primary me-2"></i>
                Filtros y Exportación
            </h5>

            <p class="text-muted mb-0">
                Consulta información por rangos de fechas y exporta reportes del sistema.
            </p>
        </div>

        //BOTONES RÁPIDOS
        <div class="d-flex flex-wrap gap-2">

            <button type="button"
                class="btn btn-outline-primary btnFiltroFecha"
                data-tipo="hoy">
                <i class="fa-solid fa-calendar-day me-1"></i>

                Hoy
            </button>

            <button type="button"
                class="btn btn-outline-primary btnFiltroFecha"
                data-tipo="semana">
                <i class="fa-solid fa-calendar-week me-1"></i>

                Semana
            </button>

            <button type="button"
                class="btn btn-outline-primary btnFiltroFecha"
                data-tipo="mes">
                <i class="fa-solid fa-calendar-alt me-1"></i>

                Mes
            </button>

            <button type="button"
                class="btn btn-outline-primary btnFiltroFecha"
                data-tipo="anio">
                <i class="fa-solid fa-calendar me-1"></i>

                Año
            </button>

        </div>

    </div>

    //FORMULARIO
    <form method="GET">

        <div class="row g-3 align-items-end">

            //DESDE 
            <div class="col-lg-4">

                <label class="form-label fw-semibold">
                    Fecha Inicial
                </label>

                <input
                    type="date"
                    name="inicio"
                    class="form-control"
                    value="<= htmlspecialchars($fechaInicio) ?>">

            </div>

            //HASTA
            <div class="col-lg-4">

                <label class="form-label fw-semibold">
                    Fecha Final
                </label>

                <input
                    type="date"
                    name="fin"
                    class="form-control"
                    value="<= htmlspecialchars($fechaFin) ?>">

            </div>

            //BOTONES
            <div class="col-lg-4">

                <div class="d-grid gap-2 d-md-flex">

                    <button
                        type="submit"
                        name="tipo"
                        value="personalizado"
                        class="btn btn-success flex-fill">

                        <i class="fa-solid fa-filter me-1"></i>
                        Aplicar Filtro
                    </button>

                    <a
                        href="?tipo=hoy"
                        class="btn btn-outline-secondary flex-fill">

                        <i class="fa-solid fa-rotate-left me-1"></i>
                        Limpiar
                    </a>

                </div>

            </div>

        </div>

    </form>
    
    //INPUT OCULTO
    <input type="hidden" id="tipoFiltro" value="<?= $tipo ?>">

    //INFORMACIÓN DEL RANGO
    <div class="row mt-4">

        <div class="col-lg-6">

            <div class="alert alert-light border h-100 mb-0">

                <div class="d-flex align-items-center mb-2">

                    <i class="fa-solid fa-circle-info text-primary me-2"></i>

                    <strong>
                        Información Actual
                    </strong>

                </div>

                <div class="small">

                    <div class="mb-1">
                        <strong>Tipo:</strong>
                        <= ucfirst($tipo) ?>
                    </div>

                    <div>
                        <strong>Rango:</strong>
                        <= date('d/m/Y', strtotime($fechaInicio)) ?>
                        —
                        <= date('d/m/Y', strtotime($fechaFin)) ?>
                    </div>

                </div>

            </div>

        </div>

        //EXPORTACIONES
        <div class="col-lg-6 mt-3 mt-lg-0">

            <div class="card border-0 bg-light h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-download text-success me-2"></i>

                        <strong>
                            Exportaciones
                        </strong>

                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <a id="btnExcel"
                            href="#"
                            class="btn btn-success">

                            <i class="fa-solid fa-file-excel me-1"></i>
                            Excel
                        </a>

                        <a id="btnPDF"
                            href="#"
                            class="btn btn-danger">

                            <i class="fa-solid fa-file-pdf me-1"></i>
                            PDF
                        </a>

                        <button
                            onclick="window.print()"
                            class="btn btn-dark">

                            <i class="fa-solid fa-print me-1"></i>
                            Imprimir
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</div> -->

    <div class="row g-2 mb-3">

        <!-- TOTAL -->
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2">
                <small class="text-muted d-block">TOTAL</small>
                <h5 class="fw-bold mb-0">
                    <?= number_format($total) ?>
                </h5>
            </div>
        </div>

        <!-- FINALIZADOS -->
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2">
                <small class="text-muted d-block">FINALIZADOS</small>
                <h5 class="fw-bold text-success mb-0">
                    <?= number_format($atendidos) ?>
                </h5>
            </div>
        </div>

        <!-- PENDIENTES -->
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2">
                <small class="text-muted d-block">PENDIENTES</small>
                <h5 class="fw-bold text-warning mb-0">
                    <?= number_format($pendientes) ?>
                </h5>
            </div>
        </div>

        <!-- ESPERA -->
        <div class="col-lg-3 col-6">
            <div class="card border-0 shadow-sm text-center p-2">
                <small class="text-muted d-block">ESPERA</small>
                <h5 class="fw-bold text-primary mb-0">
                    <?= number_format($promedioEspera) ?>m
                </h5>
            </div>
        </div>

    </div>


    <div class="card shadow-sm border-0 dashboard-card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-table text-primary me-2"></i>
                        Resultados de Consulta
                    </h5>

                    <small class="text-muted">
                        Tickets encontrados:
                        <?= $tablaGeneral->num_rows ?>
                    </small>
                </div>

            </div>

            <div class="row g-3 mb-4">

                <!-- ESTADO -->
                <div class="col-lg-4">

                    <label class="form-label fw-semibold">
                        Estado
                    </label>

                    <select id="filtroEstado" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="PENDIENTE">
                            PENDIENTE
                        </option>

                        <option value="LLAMADO">
                            LLAMADO
                        </option>

                        <option value="EN_ATENCION">
                            EN ATENCION
                        </option>

                        <option value="FINALIZADO">
                            FINALIZADO
                        </option>

                        <option value="CANCELADO">
                            CANCELADO
                        </option>

                    </select>

                </div>

                <!-- SERVICIO -->
                <div class="col-lg-4">

                    <label class="form-label fw-semibold">
                        Servicio
                    </label>

                    <select id="filtroServicio" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <?php

                        $servicios = $conexion->query("
                SELECT id_servicios, nombre_serv
                FROM servicios
                ORDER BY nombre_serv ASC
            ");

                        while ($s = $servicios->fetch_assoc()):
                        ?>

                            <option value="<?= $s['id_servicios'] ?>">
                                <?= $s['nombre_serv'] ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- OPERADOR -->
                <div class="col-lg-4">

                    <label class="form-label fw-semibold">
                        Operador
                    </label>

                    <select id="filtroOperador" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <?php

                        $usuarios = $conexion->query("
                SELECT id_usuario, nombre_user
                FROM usuarios
                ORDER BY nombre_user ASC
            ");

                        while ($u = $usuarios->fetch_assoc()):
                        ?>

                            <option value="<?= $u['id_usuario'] ?>">
                                <?= $u['nombre_user'] ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

            </div>

            <div class="table-responsive">

                <table id="tablaTickets"
                    class="table table-hover table-striped align-middle w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Operador</th>
                            <th>Fecha</th>
                            <th>Creado</th>
                            <th>Llamado</th>
                            <th>En atencion</th>
                            <th>Finalizado</th>
                            <!-- <th>Espera</th>
                            <th>Atención</th> -->
                        </tr>
                    </thead>

                    <!-- <tbody>
                        <php $i = 1; ?>
                        <php while ($row = $tablaGeneral->fetch_assoc()): ?>
                            <tr>
                                <td><= $i++ ?></td>
                                <td><= $row['numero_tk'] ?></td>
                                <td><= $row['nombre_serv'] ?></td>
                                <td>
                                    <php if ($row['estado_tk'] == 'FINALIZADO'): ?>
                                        <span class="badge bg-success">
                                            FINALIZADO
                                        </span>
                                    <php elseif ($row['estado_tk'] == 'PENDIENTE'): ?>
                                        <span class="badge bg-warning text-dark">
                                            PENDIENTE
                                        </span>
                                    <php elseif ($row['estado_tk'] == 'CANCELADO'): ?>
                                        <span class="badge bg-danger">
                                            CANCELADO
                                        </span>
                                    <php else: ?>
                                        <span class="badge bg-primary">
                                            <= $row['estado_tk'] ?>
                                        </span>
                                    <php endif; ?>
                                </td>
                                <td><= $row['nombre_user'] ?></td>
                                <td><= $row['fecha_tk'] ?></td>
                                <td><= $row['tiempo_espera'] ?> min</td>
                                <td><= $row['tiempo_atencion'] ?> min</td>
                            </tr>
                        <php endwhile; ?>
                    </tbody> -->

                </table>
            </div>
        </div>
    </div>
</div>


<!-- JQUERY -->
<script src="<?= BASE_URL ?>assets/plugins/js/jquery-3.7.1.min.js"></script>

<!-- DATATABLES 
<link rel="stylesheet"
    href="<= BASE_URL ?>assets/plugins/css/dataTables.bootstrap5.min.css">-->

<script src="<?= BASE_URL ?>assets/plugins/js/jquery.dataTables.min.js"></script>

<script src="<?= BASE_URL ?>assets/plugins/js/dataTables.bootstrap5.min.js"></script>

<!-- TU JS -->
<script src="<?= BASE_URL ?>public/js/consultaGeneral.js"></script>

<?php

include "../footer.php";

?>