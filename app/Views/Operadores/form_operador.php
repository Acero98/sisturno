<?php $editando = isset($operador); ?>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" value="<?= $editando ? htmlspecialchars($operador['nombre_user']) : '' ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">DNI</label><input type="text" class="form-control" name="dni" value="<?= $editando ? htmlspecialchars($operador['dni_user']) : '' ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Género</label><select name="genero" class="form-select" required>
            <option value="">Seleccionar</option>
            <option value="M" <?= $editando && $operador['genero_user'] === 'M' ? 'selected' : '' ?>>Masculino</option>
            <option value="F" <?= $editando && $operador['genero_user'] === 'F' ? 'selected' : '' ?>>Femenino</option>
        </select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Ventanilla</label><input type="text" class="form-control" name="ventanilla" value="<?= $editando ? htmlspecialchars($operador['num_ventanilla']) : '' ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Puesto</label><input type="text" class="form-control" name="puesto" value="<?= $editando ? htmlspecialchars($operador['puesto_user']) : '' ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Oficina</label><input type="text" class="form-control" name="oficina" value="<?= $editando ? htmlspecialchars($operador['oficina_user']) : '' ?>" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Usuario</label><input type="text" class="form-control" name="usuario" value="<?= $editando ? htmlspecialchars($operador['usuario_user']) : '' ?>" autocomplete="username" required></div>
    <div class="col-md-6 mb-3"><label class="form-label"><?= $editando ? 'Nueva contraseña' : 'Contraseña' ?></label><input type="password" class="form-control" name="password" autocomplete="<?= $editando ? 'new-password' : 'new-password' ?>" <?= $editando ? '' : 'required' ?>><?php if ($editando): ?><small class="form-text">Déjala vacía para conservar la actual.</small><?php endif; ?></div>
    <div class="col-md-6 mb-3"><label class="form-label">Rol</label><select name="rol" class="form-select" required>
            <option value="">Seleccionar rol</option><?php foreach ($roles as $rol): ?><option value="<?= $rol['id_rol'] ?>" <?= $editando && (int) $operador['id_rol_user'] === (int) $rol['id_rol'] ? 'selected' : '' ?>><?= htmlspecialchars($rol['nombre_rol']) ?></option><?php endforeach; ?>
        </select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Observaciones</label><textarea class="form-control" name="observaciones" rows="2"><?= $editando ? htmlspecialchars($operador['observaciones_user'] ?? '') : '' ?></textarea></div>
</div>