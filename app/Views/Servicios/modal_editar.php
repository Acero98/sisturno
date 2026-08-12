<div class="modal fade" id="modalEditarServicio<?= (int) $servicio['id_servicios'] ?>" tabindex="-1" aria-labelledby="tituloEditarServicio<?= (int) $servicio['id_servicios'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content service-modal service-modal-edit">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3"><span class="modal-icon"><i class="fa-solid fa-pen-to-square"></i></span><div><h5 class="modal-title" id="tituloEditarServicio<?= (int) $servicio['id_servicios'] ?>">Editar Servicio</h5><small>Actualiza la información del servicio</small></div></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=servicios&action=actualizar" class="formEditarServicioMvc">
                    <input type="hidden" name="id" value="<?= (int) $servicio['id_servicios'] ?>">
                    <?php require __DIR__ . '/form.php'; ?>
                    <div class="modal-footer-custom"><button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-modal-save"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
