<div class="modal fade" id="modalRegistroOperador" tabindex="-1" aria-labelledby="modalRegistroOperadorTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content operator-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <div>
                        <h5 class="modal-title mb-0" id="modalRegistroOperadorTitulo">Registrar Operador</h5><small>Crea un usuario para la atención de turnos</small>
                    </div>
                </div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=operadores&action=registrar" class="formRegistrarOperador">
                    <?php require __DIR__ . '/form_operador.php'; ?>
                    <div class="modal-footer-custom"><button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-modal-create"><i class="fa-solid fa-user-plus me-2"></i>Registrar Operador</button></div>
                </form>
            </div>
        </div>
    </div>
</div>