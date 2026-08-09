<!-- MODAL REGISTRAR USUARIO -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content user-modal">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-icon modal-icon-create"><i class="fa-solid fa-user-plus"></i></div>
                    <div>
                        <h5 class="modal-title mb-0" id="modalRegistroTitulo">Registrar Usuario</h5>
                        <small>Crea un nuevo usuario para el sistema</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>index.php?ruta=usuarios&action=registrar" class="formRegistrarUsuario">
                    <div class="mb-3">
                        <label class="form-label" for="registroNombre">Nombre</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-user"></i>
                            <input id="registroNombre" type="text" class="form-control" name="nombre" placeholder="Nombre completo" autocomplete="name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="registroUsuario">Usuario</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-at"></i>
                            <input id="registroUsuario" type="text" class="form-control" name="usuario" placeholder="Nombre de usuario" autocomplete="username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="registroPassword">Contraseña</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input id="registroPassword" type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="new-password" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="registroRol">Rol</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-shield-halved"></i>
                            <select id="registroRol" name="rol" class="form-select" required>
                                <option value="">Seleccionar rol</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?= $rol['id_rol'] ?>">
                                        <?= htmlspecialchars($rol['nombre_rol']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-modal-create btnConfirmarRegistro">
                            <i class="fa-solid fa-user-plus me-2"></i>Registrar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
