<!-- MODAL EDITAR -->
<div class="modal fade"
    id="modalEditar<?= $datos->id_usuario ?>"
    tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content user-modal user-modal-edit">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">
                            Editar Usuario
                        </h5>
                        <small>
                            Modifica los datos de acceso
                        </small>
                    </div>
                </div>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST"
                    action="<?= BASE_URL ?>index.php?ruta=usuarios&action=actualizar"
                    class="formEditarUsuario">
                    <input type="hidden"
                        name="id"
                        value="<?= $datos->id_usuario ?>">
                    <div class="mb-3">
                        <label class="form-label">
                            Nombre
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-user"></i>
                            <input type="text"
                                class="form-control"
                                name="nombre"
                                value="<?= htmlspecialchars($datos->nombre_user) ?>"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Usuario
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-at"></i>
                            <input type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($datos->usuario_user) ?>"
                                readonly>
                            <div class="form-text">El nombre de usuario no se puede modificar después del registro.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Nueva Contraseña
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password"
                                class="form-control"
                                name="password">
                        </div>
                        <small class="form-text">
                            Dejar vacío si no desea cambiarla.
                        </small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">
                            Rol
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-shield-halved"></i>
                            <select name="rol"
                                class="form-select"
                                required>
                                <?php foreach ($roles as $rol): ?>
                                    <option
                                        value="<?= $rol['id_rol'] ?>"
                                        <?= ($rol['id_rol'] == $datos->id_rol_user)
                                            ? 'selected'
                                            : '' ?>>
                                        <?= htmlspecialchars($rol['nombre_rol']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button"
                            class="btn btn-modal-cancel"
                            data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="btn btn-modal-save">
                            <i class="fa-solid fa-check me-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
