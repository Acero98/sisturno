<div class="modal fade" id="modalEditarOperador<?= $operador['id_usuario'] ?>" tabindex="-1" aria-labelledby="modalEditarOperadorTitulo<?= $operador['id_usuario'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content operator-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-icon"><i class="fa-solid fa-user-pen"></i></div>
                    <div>
                        <h5 class="modal-title mb-0" id="modalEditarOperadorTitulo<?= $operador['id_usuario'] ?>">Editar Operador</h5><small>Actualiza su perfil de atención</small>
                    </div>
                </div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=operadores&action=actualizar" class="formEditarOperador">
                    <input type="hidden" name="id" value="<?= $operador['id_usuario'] ?>">
                    <?php require __DIR__ . '/form_operador.php'; ?>
                    <div class="modal-footer-custom"><button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-modal-save"><i class="fa-solid fa-check me-2"></i>Guardar cambios</button></div>
                </form>
            </div>
        </div>
    </div>
</div>