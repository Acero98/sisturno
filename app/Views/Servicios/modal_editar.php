<div class="modal fade" id="modalEditarServicio<?= $servicio['id_servicios'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Editar Servicio</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=servicios&action=actualizar" class="formEditarServicioMvc"><input type="hidden" name="id" value="<?= $servicio['id_servicios'] ?>"><?php require __DIR__ . '/form.php'; ?><div class="d-grid"><button type="submit" class="btn btn-warning"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>
</div>