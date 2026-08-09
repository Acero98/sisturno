<div class="container-fluid bg-light py-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary mb-2 titulo-principal">BIENVENIDO</h1>
        <p class="text-secondary mb-0 subtitulo-principal">Presione el servicio que desea solicitar para generar su ticket.</p>
    </div>

    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php foreach ($servicios as $servicio): ?>
                <div class="col-6 col-md-4 col-xl-3">
                    <form method="POST" class="form-generar-ticket h-100">
                        <input type="hidden" name="id_servicios" value="<?= (int) $servicio['id_servicios'] ?>">
                        <button type="submit" name="generar_turno" class="btn btn-primary w-100 h-100 p-3 border-0 shadow rounded-4 boton-servicio">
                            <div class="fw-bold text-uppercase" style="font-size: 1.7rem; line-height: 1.3;">
                                <?= htmlspecialchars(strtoupper($servicio['nombre_serv']), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
