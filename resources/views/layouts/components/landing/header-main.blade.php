<style>
    :root {
        --bs-primary: #8fbd56;
        --bs-primary-rgb: 143, 189, 86;
    }
    .btn-primary {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    .btn-primary:hover {
        background-color: #80aa4c;
        border-color: #80aa4c;
    }
    .bg-primary {
        background-color: var(--bs-primary) !important;
    }
    .text-primary {
        color: var(--bs-primary) !important;
    }
    .hero-section {
        min-height: 600px;
        padding-top: 80px;
        position: relative;
        display: flex;
        align-items: center;
    }
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
    }
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .feature-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1.5rem;
    }
    .testimonial-card {
        height: 100%;
    }
    .social-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }
</style>
<style>
    :where([class^="ri-"])::before { content: "\f3c2"; }
    body {
        font-family: 'Inter', sans-serif;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .hero-section {
        background-image: url('https://readdy.ai/api/search-image?query=Happy%20children%20playing%20sports%20together%2C%20diverse%20group%20of%20kids%20in%20sports%20uniforms%2C%20teamwork%2C%20joy%2C%20achievement%2C%20bright%20sunny%20day%2C%20natural%20outdoor%20setting%2C%20green%20field%2C%20blurred%20background%20on%20the%20left%20side%20for%20text%20placement%2C%20professional%20sports%20photography%20style&width=1920&height=800&seq=1234&orientation=landscape');
        background-size: cover;
        background-position: center;
    }
</style>
<!-- Hero секция -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row">
            <div class="col-lg-8 col-xl-6">
                <h1 class="display-6 text-white fw-bold mb-4">
                    Каждый ребёнок — чемпион, каждая команда — семья!
                </h1>
                <p class="lead text-white opacity-90 mb-5">
                    Платформа для детских спортивных клубов, которая объединяет
                    команды, тренеров и родителей
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{route('auth.register')}}" class="btn btn-primary btn-lg">Начать бесплатно</a>
                    <button class="btn btn-light btn-lg">Узнать больше</button>
                </div>
            </div>
        </div>
    </div>
</section>
