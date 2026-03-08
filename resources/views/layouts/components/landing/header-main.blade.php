<style>
    :root {
        --bs-primary: #8fbd56;
        --bs-primary-rgb: 143, 189, 86;
        --primary-dark: #6d9e3a;
    }
    body { font-family: 'Inter', sans-serif; }

    /* Buttons */
    .btn-primary { background-color: var(--bs-primary); border-color: var(--bs-primary); }
    .btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); }
    .btn-outline-primary { color: var(--bs-primary); border-color: var(--bs-primary); }
    .btn-outline-primary:hover { background-color: var(--bs-primary); border-color: var(--bs-primary); color: #fff; }
    .bg-primary { background-color: var(--bs-primary) !important; }
    .text-primary { color: var(--bs-primary) !important; }
    .border-primary { border-color: var(--bs-primary) !important; }

    /* Hero */
    .hero-section {
        min-height: 680px;
        padding: 100px 0 60px;
        position: relative;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #1a2e0a 0%, #2d4a14 50%, #3d6420 100%);
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 70%;
        height: 200%;
        background: radial-gradient(ellipse, rgba(143,189,86,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-stat-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: transform 0.3s;
    }
    .hero-stat-card:hover { transform: translateY(-4px); }
    .hero-stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--bs-primary);
        line-height: 1;
    }
    .hero-stat-label {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        margin-top: 6px;
    }

    /* Sections */
    .section-badge {
        display: inline-block;
        background: rgba(143,189,86,0.1);
        color: var(--bs-primary);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    /* Feature cards */
    .feature-card {
        border: 1px solid #e9ecef;
        border-radius: 16px;
        padding: 32px 24px;
        transition: all 0.3s;
        background: #fff;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.08);
        border-color: var(--bs-primary);
    }
    .feature-icon-wrap {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        margin-bottom: 20px;
        font-size: 1.5rem;
    }

    /* Role cards */
    .role-card {
        border-radius: 20px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: all 0.3s;
        height: 100%;
    }
    .role-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    }
    .role-card .role-header {
        padding: 28px 24px 20px;
    }
    .role-card .role-header .role-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 12px;
    }
    .role-card .role-body {
        padding: 0 24px 24px;
    }
    .role-card .role-body li {
        padding: 6px 0;
        font-size: 0.92rem;
        color: #5a6a78;
        display: flex;
        align-items: flex-start;
    }
    .role-card .role-body li .check-icon {
        color: var(--bs-primary);
        margin-right: 8px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* Stats / Charts */
    .stats-section { background: #f8faf5; }
    .chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        height: 100%;
    }
    .chart-card h5 { font-size: 1rem; font-weight: 600; margin-bottom: 16px; }

    /* Testimonial */
    .testimonial-card-new {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        height: 100%;
        border: 1px solid #f0f0f0;
    }
    .testimonial-card-new:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .testimonial-stars { color: #f59e0b; margin-bottom: 12px; }

    /* CTA */
    .cta-section {
        background: linear-gradient(135deg, #2d4a14 0%, #4a7a25 100%);
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: -30%;
        left: -10%;
        width: 50%;
        height: 160%;
        background: radial-gradient(ellipse, rgba(143,189,86,0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Misc */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<!-- Hero секция -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="section-badge bg-transparent border border-success border-opacity-50 text-white mb-3">
                    <span style="color: var(--bs-primary);">&#9679;</span>&ensp;Платформа для детского спорта
                </div>
                <h1 class="display-4 text-white fw-bold mb-4" style="line-height: 1.15;">
                    Управляйте клубами,<br>
                    командами и турнирами<br>
                    <span style="color: var(--bs-primary);">в одном месте</span>
                </h1>
                <p class="lead text-white opacity-75 mb-5" style="max-width: 520px;">
                    «Детская лига» объединяет тренеров, родителей и юных спортсменов. Планируйте тренировки, ведите статистику матчей и отслеживайте прогресс каждого ребёнка.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('auth.register') }}" class="btn btn-primary btn-lg px-4">
                        Начать бесплатно
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg px-4">
                        Узнать больше
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-number">12+</div>
                            <div class="hero-stat-label">Видов спорта</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-number">50+</div>
                            <div class="hero-stat-label">Клубов</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-number">200+</div>
                            <div class="hero-stat-label">Команд</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-number">3000+</div>
                            <div class="hero-stat-label">Юных спортсменов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
