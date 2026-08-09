<style>
    /* =========================================================
   WELCOME CARD
   ========================================================= */

    .welcome-card {
        min-height: auto;

        background: #eef2ff;

        border: 1px solid #c7d2fe;

        border-radius: 18px;

        box-shadow:
            0 2px 6px rgba(15, 23, 42, 0.04),
            0 8px 18px rgba(15, 23, 42, 0.04);

        transition: all 0.2s ease;
    }

    .welcome-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 4px 10px rgba(15, 23, 42, 0.06),
            0 12px 25px rgba(15, 23, 42, 0.07);
    }


    /* =========================================================
   ICONO PEQUEÑO
   ========================================================= */

    .welcome-icon {
        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #dbeafe;

        color: #2563eb;

        font-size: 1rem;
    }


    /* =========================================================
   ETIQUETA
   ========================================================= */

    .welcome-label {
        font-size: 0.9rem;

        font-weight: 600;

        color: #2563eb;

        letter-spacing: 0.2px;
    }


    /* =========================================================
   TÍTULO
   ========================================================= */

    .welcome-title {
        margin: 0;

        font-size: 1.65rem;

        line-height: 1.25;

        font-weight: 700;

        color: #0f172a;
    }


    /* =========================================================
   TEXTO
   ========================================================= */

    .welcome-text {
        font-size: 1rem;

        color: #64748b;
    }

    .welcome-text strong {
        color: #334155;

        font-weight: 700;
    }


    /* =========================================================
   FECHA / HORA
   ========================================================= */

    .welcome-info {
        display: inline-flex;

        align-items: center;

        padding: 8px 13px;

        border-radius: 10px;

        background: rgba(255, 255, 255, 0.65);

        border: 1px solid rgba(255, 255, 255, 0.8);

        color: #475569;

        font-size: 0.85rem;

        font-weight: 500;
    }

    .welcome-info i {
        color: #6366f1;
    }


    /* =========================================================
   ICONO GRANDE
   ========================================================= */

    .welcome-illustration {
        width: 110px;
        height: 110px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 28px;

        background: #dbeafe;

        color: #2563eb;

        font-size: 3.5rem;

        box-shadow:
            inset 0 0 0 1px rgba(255, 255, 255, 0.5);
    }
</style>

<div class="col-12">

    <div class="welcome-card position-relative overflow-hidden px-4 py-3">

        <div class="row align-items-center position-relative">

            <!-- Información -->
            <div class="col-md-9">

                <div class="d-flex align-items-center flex-wrap gap-3">

                    <!-- Etiqueta -->
                    <div class="d-flex align-items-center gap-2">

                        <div class="welcome-icon">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>

                        <span class="welcome-label">
                            PANEL PRINCIPAL
                        </span>

                    </div>

                    <!-- Título -->
                    <h3 class="welcome-title mb-0">
                        Dashboard del Sistema de Turnos
                    </h3>

                </div>

                <!-- Usuario + información -->
                <div class="d-flex align-items-center flex-wrap gap-3 mt-2">

                    <p class="welcome-text mb-0">
                        Bienvenido,
                        <strong>
                            <?= htmlspecialchars(strtoupper($_SESSION['usuario'])) ?>
                        </strong>
                    </p>

                    <span class="welcome-info">
                        <i class="fa-regular fa-calendar me-1"></i>
                        <?= $fechaActual ?>
                    </span>

                    <span class="welcome-info">
                        <i class="fa-regular fa-clock me-1"></i>
                        <?= $horaActual ?>
                    </span>

                </div>

            </div>

            <!-- Icono -->
            <div class="col-md-3 d-none d-md-flex justify-content-end">

                <div class="welcome-illustration">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

            </div>

        </div>

    </div>

</div>