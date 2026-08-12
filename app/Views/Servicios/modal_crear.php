<div class="modal fade" id="modalRegistroServicio" tabindex="-1" aria-labelledby="tituloRegistroServicio" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content service-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3"><span class="modal-icon modal-icon-create"><i class="fa-solid fa-plus"></i></span><div><h5 class="modal-title" id="tituloRegistroServicio">Registrar Servicio</h5><small>Crea un nuevo servicio para la atención de turnos</small></div></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=servicios&action=registrar" class="formRegistrarServicioMvc">
                    <?php require __DIR__ . '/form.php'; ?>
                    <div class="modal-footer-custom"><button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-modal-create"><i class="fa-solid fa-plus me-2"></i>Registrar</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
