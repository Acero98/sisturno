<div class="modal fade" id="modalRegistroServicio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Servicio</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=servicios&action=registrar" class="formRegistrarServicioMvc"><?php require __DIR__ . '/form.php'; ?><div class="d-grid"><button type="submit" class="btn btn-primary">Registrar</button></div>
                </form>
            </div>
        </div>
    </div>
</div>