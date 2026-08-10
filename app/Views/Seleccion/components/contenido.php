<?php
$cantidadServicios = count($servicios);
$densidadServicios = $cantidadServicios > 12 ? 'densidad-alta' : ($cantidadServicios > 8 ? 'densidad-media' : 'densidad-normal');
?>
<main class="seleccion-pantalla">
    <header class="seleccion-encabezado text-center">
        <h1>BIENVENIDO</h1>
        <p>Presione el servicio que desea solicitar para generar su ticket.</p>
    </header>

    <?php if ($cantidadServicios > 0): ?>
        <section class="seleccion-servicios-grid <?= $densidadServicios ?>" aria-label="Servicios disponibles">
            <?php foreach ($servicios as $servicio): ?>
                <form method="POST" class="form-generar-ticket">
                    <input type="hidden" name="id_servicios" value="<?= (int) $servicio['id_servicios'] ?>">
                    <button type="submit" name="generar_turno" class="seleccion-servicio shadow-sm">
                        <span class="seleccion-servicio-nombre"><?= htmlspecialchars(strtoupper($servicio['nombre_serv']), ENT_QUOTES, 'UTF-8') ?></span>
                    </button>
                </form>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <div class="seleccion-vacia"><i class="fa-solid fa-circle-info me-2"></i>No hay servicios disponibles en este momento.</div>
    <?php endif; ?>
</main>
