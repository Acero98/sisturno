<style>
    /* =========================================================
   KPI CARDS
   ========================================================= */

    .metric-card {
        position: relative;
        overflow: hidden;

        min-height: auto;
        padding: 28px 30px;

        border-radius: 18px;

        border: 1px solid rgba(148, 163, 184, 0.18);

        box-shadow:
            0 2px 6px rgba(15, 23, 42, 0.04),
            0 8px 18px rgba(15, 23, 42, 0.04);

        transition: all 0.2s ease;
    }

    .metric-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 4px 10px rgba(15, 23, 42, 0.06),
            0 12px 25px rgba(15, 23, 42, 0.07);
    }


    /* =========================================================
   TÍTULO DEL KPI
   ========================================================= */

    .metric-card small {
        display: block;

        margin-bottom: 14px;

        font-size: 1rem;
        font-weight: 600;

        letter-spacing: 0;

        color: inherit;
    }


    /* =========================================================
   VALOR
   ========================================================= */

    .metric-card h2 {
        margin: 0;

        font-size: 2.15rem;
        line-height: 1.1;

        font-weight: 700;

        color: #0f172a;
    }


    /* =========================================================
   ICONO
   ========================================================= */

    .metric-card>i {
        position: absolute;

        right: 28px;
        top: 50%;

        transform: translateY(-35%);

        width: 70px;
        height: 70px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 18px;

        font-size: 1.9rem;

        opacity: 1;
    }

    .metric-description {
        display: block;

        margin-top: 8px;

        font-size: 0.88rem;

        font-weight: 500;

        color: #64748b;
    }


    /* =========================================================
   AZUL
   ========================================================= */

    .gradient-blue {
        background: #eff6ff;
        color: #2563eb;
        border-color: #dbeafe;
    }

    .gradient-blue>i {
        background: #dbeafe;
        color: #2563eb;
    }


    /* =========================================================
   NARANJA
   ========================================================= */

    .gradient-orange {
        background: #fff7ed;
        color: #ea580c;
        border-color: #fed7aa;
    }

    .gradient-orange>i {
        background: #ffedd5;
        color: #ea580c;
    }


    /* =========================================================
   CELESTE
   ========================================================= */

    .gradient-info {
        background: #ecfeff;
        color: #0891b2;
        border-color: #cffafe;
    }

    .gradient-info>i {
        background: #cffafe;
        color: #0891b2;
    }


    /* =========================================================
   MORADO
   ========================================================= */

    .gradient-purple {
        background: #faf5ff;
        color: #9333ea;
        border-color: #e9d5ff;
    }

    .gradient-purple>i {
        background: #f3e8ff;
        color: #9333ea;
    }


    /* =========================================================
   VERDE
   ========================================================= */

    .gradient-green {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }

    .gradient-green>i {
        background: #d1fae5;
        color: #059669;
    }


    /* =========================================================
   ROJO
   ========================================================= */

    .gradient-red {
        background: #fff1f2;
        color: #e11d48;
        border-color: #fecdd3;
    }

    .gradient-red>i {
        background: #ffe4e6;
        color: #e11d48;
    }


    /* =========================================================
   CELESTE - TIEMPOS
   ========================================================= */

    .gradient-celeste {
        background: #ecfeff;
        color: #0891b2;
        border-color: #a5f3fc;
    }

    .gradient-celeste>i {
        background: #cffafe;
        color: #0891b2;
    }
</style>
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-4 col-sm-6">

        <div class="metric-card gradient-blue">

            <small>
                Total de Tickets
            </small>

            <h2>
                <?= number_format($total) ?>
            </h2>

            <i class="fa-solid fa-ticket metric-icon"></i>

        </div>

    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-orange dashboard-card">
            <small>PENDIENTES</small>
            <h2>
                <?= number_format($pendientes) ?>
            </h2>
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-info dashboard-card">
            <small>LLAMADOS</small>
            <h2>
                <?= number_format($llamados) ?>
            </h2>
            <i class="fa-solid fa-bullhorn"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-purple dashboard-card">
            <small>EN ATENCIÓN</small>
            <h2>
                <?= number_format($en_atencion) ?>
            </h2>
            <i class="fa-solid fa-user-clock"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-green dashboard-card">
            <small>FINALIZADOS</small>
            <h2>
                <?= number_format($atendidos) ?>
            </h2>
            <i class="fa-solid fa-check-circle"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-red dashboard-card">
            <small>CANCELADOS</small>
            <h2>
                <?= number_format($cancelados) ?>
            </h2>
            <i class="fa-solid fa-times-circle"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-celeste dashboard-card">
            <small>TIEMPO ESPERA</small>
            <h2>
                <?= number_format($promedioEspera) ?> min
            </h2>
            <i class="fa-solid fa-stopwatch"></i>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="metric-card gradient-celeste dashboard-card">
            <small>TIEMPO ATENCIÓN</small>
            <h2>
                <?= number_format($promedioAtencion) ?> min
            </h2>
            <i class="fa-solid fa-user-clock"></i>
        </div>
    </div>
</div>