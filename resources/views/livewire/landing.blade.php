@extends('layouts.landing-app')

@section('styles')
@endsection

@section('content')
    {{-- Возможности платформы --}}
    <section id="features" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-4">Возможности нашей платформы</h2>
                <p class="lead text-muted mx-auto" style="max-width: 800px">
                    Современные инструменты для управления детскими спортивными командами и клубами
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-team-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Регистрация команд</h3>
                            <p class="card-text text-muted">
                                Создавайте профили команд, добавляйте игроков и тренеров.
                                Управляйте составом и ролями участников.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-calendar-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Расписание тренировок</h3>
                            <p class="card-text text-muted">
                                Планируйте занятия и уведомляйте участников. Отслеживайте
                                посещаемость и прогресс команды.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-trophy-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Регистрация соревнований</h3>
                            <p class="card-text text-muted">
                                Организуйте турниры и управляйте заявками онлайн. Создавайте
                                расписания матчей и публикуйте результаты.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-line-chart-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Учёт результатов</h3>
                            <p class="card-text text-muted">
                                Отслеживайте статистику команд и игроков. Анализируйте прогресс и
                                выявляйте сильные стороны спортсменов.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Для кого платформа --}}
    <section id="audience" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-4">Для кого наша платформа</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-user-star-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Тренеры</h3>
                            <p class="card-text text-muted">
                                Планируйте тренировки, управляйте составами, отмечайте посещаемость
                                и ведите статистику матчей — всё в одном месте.
                            </p>
                            <a href="{{ route('auth.register') }}" class="btn btn-outline-primary mt-2">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-parent-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Родители</h3>
                            <p class="card-text text-muted">
                                Следите за расписанием, результатами матчей и прогрессом вашего ребёнка.
                                Будьте в курсе всех событий команды.
                            </p>
                            <a href="{{ route('auth.register') }}" class="btn btn-outline-primary mt-2">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="ri-building-2-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Клубы и организаторы</h3>
                            <p class="card-text text-muted">
                                Управляйте клубом, командами и площадками. Организуйте турниры
                                и контролируйте все процессы.
                            </p>
                            <a href="{{ route('auth.register') }}" class="btn btn-outline-primary mt-2">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Преимущества платформы --}}
    <section id="advantages" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <img
                        src="{{ asset('assets/images/brand/logo.png') }}"
                        alt="Детская лига"
                        class="rounded-3 img-fluid mx-auto d-block"
                        style="max-height: 400px"
                    />
                </div>
                <div class="col-lg-6">
                    <h2 class="display-5 mb-4">Преимущества нашей платформы</h2>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded-circle p-3 me-3 flex-shrink-0"
                                 style="width: 48px; height: 48px;">
                                <i class="ri-check-line ri-lg d-flex justify-content-center"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Экономия времени</h3>
                                <p class="text-muted mb-0">
                                    Автоматизация организационных задач позволяет тренерам уделять больше внимания развитию детей
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded-circle p-3 me-3 flex-shrink-0"
                                 style="width: 48px; height: 48px;">
                                <i class="ri-check-line ri-lg d-flex justify-content-center"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Эффективная коммуникация</h3>
                                <p class="text-muted mb-0">
                                    Удобные инструменты для общения между тренерами, родителями и юными спортсменами
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded-circle p-3 me-3 flex-shrink-0"
                                 style="width: 48px; height: 48px;">
                                <i class="ri-check-line ri-lg d-flex justify-content-center"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Аналитика прогресса</h3>
                                <p class="text-muted mb-0">
                                    Наглядные отчёты о развитии навыков, достижениях и участии в соревнованиях каждого ребёнка
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded-circle p-3 me-3 flex-shrink-0"
                                 style="width: 48px; height: 48px;">
                                <i class="ri-check-line ri-lg d-flex justify-content-center"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Разграничение доступа</h3>
                                <p class="text-muted mb-0">
                                    Гибкая система ролей: тренер, игрок, родитель, администратор. Каждый видит только то, что ему нужно
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Отзывы --}}
    <section id="testimonials" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Что говорят наши пользователи</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="card-text mb-4">
                                «С помощью этого сайта мы легко зарегистрировали команду, назначили тренировки и уже участвуем в турнирах. Отличный сервис!»
                            </p>
                            <h5 class="card-title mb-1">Анна Петрова</h5>
                            <p class="card-subtitle text-muted small">Тренер команды «Юные чемпионы»</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="card-text mb-4">
                                «Очень удобно отслеживать расписание тренировок и результаты матчей. Дети в восторге!»
                            </p>
                            <h5 class="card-title mb-1">Максим Иванов</h5>
                            <p class="card-subtitle text-muted small">Родитель игрока</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="card-text mb-4">
                                «Удобный интерфейс и быстрый отклик поддержки. Рекомендую всем футбольным клубам!»
                            </p>
                            <h5 class="card-title mb-1">Екатерина Смирнова</h5>
                            <p class="card-subtitle text-muted small">Администратор «Лига Стар»</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Призыв к действию --}}
    <section id="cta" class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="display-5 mb-3">
                Присоединяйтесь к нашей спортивной платформе
            </h2>
            <p class="lead mb-4">
                Зарегистрируйте вашу детскую команду, добавьте игроков и начните пользоваться всеми преимуществами уже сегодня!
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('auth.register') }}" class="btn btn-light btn-lg">
                    Зарегистрироваться
                </a>
                <a href="{{ route('auth.loginForm') }}" class="btn btn-outline-light btn-lg">
                    Войти в систему
                </a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
