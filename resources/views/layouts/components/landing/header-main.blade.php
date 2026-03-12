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
    
    /* Hover utilities */
    .hover-primary { transition: all 0.3s; }
    .hover-primary:hover { color: var(--bs-primary) !important; }

    /* Hero */
    .hero-section {
        min-height: 720px;
        padding: 120px 0 80px;
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
    /* Декоративный мяч */
    .hero-ball {
        position: absolute;
        border-radius: 50%;
        opacity: 0.08;
        pointer-events: none;
    }
    .hero-ball-1 {
        width: 300px;
        height: 300px;
        background: radial-gradient(circle at 30% 30%, #fff, transparent);
        top: 10%;
        right: 5%;
        animation: float 6s ease-in-out infinite;
    }
    .hero-ball-2 {
        width: 150px;
        height: 150px;
        background: radial-gradient(circle at 30% 30%, #fff, transparent);
        bottom: 15%;
        left: 3%;
        animation: float 8s ease-in-out infinite reverse;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }
    /* Линии поля */
    .field-lines {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 200px;
        opacity: 0.1;
        pointer-events: none;
    }
    
    .hero-stat-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 16px;
        padding: 24px 16px;
        text-align: center;
        transition: all 0.3s;
    }
    .hero-stat-card:hover { 
        transform: translateY(-4px); 
        background: rgba(255,255,255,0.15);
        border-color: var(--bs-primary);
    }
    .hero-stat-number {
        font-size: 2.4rem;
        font-weight: 800;
        color: var(--bs-primary);
        line-height: 1;
    }
    .hero-stat-label {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
        margin-top: 8px;
        font-weight: 500;
    }
    .hero-stat-icon {
        font-size: 1.5rem;
        margin-bottom: 8px;
        opacity: 0.8;
    }

    /* Hero Illustration */
    .hero-illustration {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Sections */
    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(143,189,86,0.1);
        color: var(--bs-primary);
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
    }
    .section-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--bs-primary);
        border-radius: 50%;
    }

    /* Feature cards */
    .feature-card {
        border: 1px solid #e9ecef;
        border-radius: 20px;
        padding: 36px 28px;
        transition: all 0.3s;
        background: #fff;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--bs-primary), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        border-color: var(--bs-primary);
    }
    .feature-card:hover::before {
        opacity: 1;
    }
    .feature-icon-wrap {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        margin-bottom: 24px;
        font-size: 1.75rem;
        transition: all 0.3s;
    }
    .feature-card:hover .feature-icon-wrap {
        transform: scale(1.1) rotate(-5deg);
    }

    /* Role cards */
    .role-card {
        border-radius: 24px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: all 0.3s;
        height: 100%;
    }
    .role-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.12);
    }
    .role-card .role-header {
        padding: 32px 24px 24px;
        position: relative;
    }
    .role-card .role-header .role-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .role-card .role-body {
        padding: 0 24px 28px;
    }
    .role-card .role-body li {
        padding: 8px 0;
        font-size: 0.95rem;
        color: #5a6a78;
        display: flex;
        align-items: flex-start;
    }
    .role-card .role-body li .check-icon {
        color: var(--bs-primary);
        margin-right: 10px;
        flex-shrink: 0;
        margin-top: 2px;
        font-weight: bold;
    }

    /* Stats / Charts */
    .stats-section { 
        background: linear-gradient(180deg, #f8faf5 0%, #fff 100%);
        position: relative;
    }
    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--bs-primary), transparent);
        opacity: 0.3;
    }
    .chart-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        height: 100%;
        border: 1px solid #f0f0f0;
        transition: all 0.3s;
    }
    .chart-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        transform: translateY(-4px);
    }
    .chart-card h5 { font-size: 1.1rem; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

    /* Steps Section */
    .step-card {
        text-align: center;
        padding: 24px;
        position: relative;
    }
    .step-number {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, var(--bs-primary), var(--primary-dark));
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(143,189,86,0.4);
        position: relative;
    }
    .step-icon {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 28px;
        height: 28px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: var(--bs-primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .step-connector {
        position: absolute;
        top: 60px;
        right: -50%;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--bs-primary), var(--bs-primary));
        opacity: 0.2;
        z-index: 0;
    }
    @media (max-width: 991px) {
        .step-connector { display: none; }
    }
    .step-card h5 {
        font-weight: 700;
        margin-bottom: 12px;
        color: #1a2e0a;
    }
    .step-card p {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Testimonial */
    .testimonials-section {
        background: linear-gradient(180deg, #fff 0%, #f8faf5 100%);
    }
    .testimonial-card-new {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        height: 100%;
        border: 1px solid #f0f0f0;
        transition: all 0.3s;
        position: relative;
    }
    .testimonial-card-new::before {
        content: '"';
        position: absolute;
        top: 10px;
        right: 24px;
        font-size: 5rem;
        color: var(--bs-primary);
        opacity: 0.1;
        font-family: Georgia, serif;
        line-height: 1;
    }
    .testimonial-card-new:hover { 
        transform: translateY(-6px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.1);
    }
    .testimonial-stars { 
        color: #f59e0b; 
        margin-bottom: 16px;
        font-size: 1.1rem;
        letter-spacing: 2px;
    }
    .testimonial-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 16px;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* CTA */
    .cta-section {
        background: linear-gradient(135deg, #2d4a14 0%, #4a7a25 50%, #3d6420 100%);
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
    .cta-section::after {
        content: '';
        position: absolute;
        bottom: -20%;
        right: -5%;
        width: 40%;
        height: 140%;
        background: radial-gradient(ellipse, rgba(255,255,255,0.05) 0%, transparent 60%);
        pointer-events: none;
    }
    .cta-decoration {
        position: absolute;
        opacity: 0.1;
        pointer-events: none;
    }

    /* Scroll animations */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Misc */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<!-- Hero секция -->
<section class="hero-section">
    <!-- Декоративные элементы -->
    <div class="hero-ball hero-ball-1"></div>
    <div class="hero-ball hero-ball-2"></div>
    
    <!-- SVG линии поля -->
    <svg class="field-lines" viewBox="0 0 1200 200" preserveAspectRatio="none">
        <line x1="0" y1="150" x2="1200" y2="150" stroke="white" stroke-width="2"/>
        <line x1="100" y1="150" x2="100" y2="50" stroke="white" stroke-width="2"/>
        <line x1="300" y1="150" x2="300" y2="80" stroke="white" stroke-width="2"/>
        <circle cx="600" cy="150" r="40" stroke="white" stroke-width="2" fill="none"/>
        <line x1="900" y1="150" x2="900" y2="80" stroke="white" stroke-width="2"/>
        <line x1="1100" y1="150" x2="1100" y2="50" stroke="white" stroke-width="2"/>
    </svg>

    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="section-badge bg-transparent border border-success border-opacity-50 text-white mb-3">
                    <span style="color: var(--bs-primary);">&#9679;</span>&ensp;Платформа для детского спорта
                </div>
                <h1 class="display-4 text-white fw-bold mb-4" style="line-height: 1.15;">
                    <span style="color: var(--bs-primary);">Сбор</span> — вся команда<br>
                    в одном месте
                </h1>
                <p class="lead text-white mb-4" id="hero-slogan" style="max-width: 520px; font-size: 1.4rem; font-weight: 500; opacity: 1; transition: opacity 0.5s ease;">
                    Сбор — команда начинается с единства.
                </p>
                <p class="lead text-white opacity-75 mb-5" style="max-width: 520px;">
                    Клуб управляет составом и расписанием, тренер ведёт тренировки и матчи, родители подтверждают участие ребёнка, а игроки видят свою команду, календарь и результаты.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('auth.register') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fe fe-user-plus me-2"></i>Начать бесплатно
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg px-5">
                        <i class="fe fe-arrow-down me-2"></i>Узнать больше
                    </a>
                </div>
                
                <!-- Trust badges -->
                <div class="d-flex flex-wrap gap-4 mt-5 pt-3">
                    <div class="d-flex align-items-center gap-2 text-white opacity-75">
                        <i class="fe fe-shield-check" style="color: var(--bs-primary);"></i>
                        <small>Безопасно</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-white opacity-75">
                        <i class="fe fe-zap" style="color: var(--bs-primary);"></i>
                        <small>Быстрая настройка</small>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-white opacity-75">
                        <i class="fe fe-smartphone" style="color: var(--bs-primary);"></i>
                        <small>Мобильное приложение</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <!-- Hero Stats Grid -->
                <div class="row g-3">
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-icon">&#9917;</div>
                            <div class="hero-stat-number">12+</div>
                            <div class="hero-stat-label">Видов спорта</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-icon">&#127942;</div>
                            <div class="hero-stat-number">50+</div>
                            <div class="hero-stat-label">Клубов</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-icon">&#128170;</div>
                            <div class="hero-stat-number">200+</div>
                            <div class="hero-stat-label">Команд</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-card">
                            <div class="hero-stat-icon">&#127939;</div>
                            <div class="hero-stat-number">3000+</div>
                            <div class="hero-stat-label">Юных спортсменов</div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating card -->
                <div class="mt-4 p-3 rounded-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--bs-primary);">
                            <i class="fe fe-check text-white"></i>
                        </div>
                        <div>
                            <div class="text-white fw-bold">Бесплатно для клубов</div>
                            <div class="text-white opacity-75 small">Бесплатно навсегда</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
