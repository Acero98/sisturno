<?php $editando = isset($servicio); ?>
<div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" value="<?= $editando ? htmlspecialchars($servicio['nombre_serv']) : '' ?>" required></div>
<div class="mb-3"><label class="form-label">Código</label><input type="text" class="form-control" name="codigo" value="<?= $editando ? htmlspecialchars($servicio['codigo_serv']) : '' ?>" required></div>
<div class="mb-3"><label class="form-label">Prioridad</label><select class="form-select" name="prioridad" required>
        <option value="">Seleccione prioridad</option><?php foreach (['NORMAL' => 'Normal', 'ALTA' => 'Alta', 'EMERGENCIA' => 'Emergencia'] as $valor => $texto): ?><option value="<?= $valor ?>" <?= $editando && $servicio['prioridad_serv'] === $valor ? 'selected' : '' ?>><?= $texto ?></option><?php endforeach; ?>
    </select></div>
<?php if (!$editando): ?><div class="mb-3"><label class="form-label">Estado</label><select class="form-select" name="estado" required>
            <option value="">Seleccione estado</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select></div><?php endif; ?>