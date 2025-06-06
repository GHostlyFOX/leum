@extends('layouts.app')

@section('styles')
    <!-- Можно добавить дополнительные стили для анимаций или кастомных элементов -->
@endsection

@section('content')
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 mb-4">Возможности нашей платформы</h2>
                <p class="lead text-muted mx-auto" style="max-width: 800px">
                    Современные инструменты для управления детскими спортивными командами и клубами
                </p>
            </div>
            <div class="row g-4">
                <!-- Карточка 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div
                                class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto"
                            >
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
                <!-- Карточка 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div
                                class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto"
                            >
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
                <!-- Карточка 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div
                                class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto"
                            >
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
                <!-- Карточка 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div
                                class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto"
                            >
                                <i class="ri-line-chart-line ri-2x"></i>
                            </div>
                            <h3 class="h5 card-title">Учет результатов</h3>
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
    <!-- Преимущества платформы -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <img
                        src="https://readdy.ai/api/search-image?query=Coach%20working%20with%20young%20athletes%2C%20diverse%20children%20in%20sports%20uniforms%2C%20training%20session%2C%20teamwork%2C%20digital%20tablet%20with%20sports%20app%20interface%20visible%2C%20indoor%20sports%20facility%2C%20professional%20sports%20photography&width=600&height=500&seq=5678&orientation=landscape"
                        alt="Тренер с командой"
                        class="rounded-3 shadow img-fluid"
                    />
                </div>
                <div class="col-lg-6">
                    <h2 class="display-5 mb-4">
                        Преимущества нашей платформы
                    </h2>
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-start">
                            <div
                                class="bg-primary text-white rounded-circle p-3 me-3"
                                style="width: 48px; height: 48px;"
                            >
                                <i
                                    class="ri-check-line ri-lg d-flex justify-content-center"
                                ></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Экономия времени</h3>
                                <p class="text-muted">
                                    Автоматизация организационных задач позволяет тренерам уделять больше внимания развитию детей
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div
                                class="bg-primary text-white rounded-circle p-3 me-3"
                                style="width: 48px; height: 48px;"
                            >
                                <i
                                    class="ri-check-line ri-lg d-flex justify-content-center"
                                ></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Эффективная коммуникация</h3>
                                <p class="text-muted">
                                    Удобные инструменты для общения между тренерами, родителями и юными спортсменами
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div
                                class="bg-primary text-white rounded-circle p-3 me-3"
                                style="width: 48px; height: 48px;"
                            >
                                <i
                                    class="ri-check-line ri-lg d-flex justify-content-center"
                                ></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Аналитика прогресса</h3>
                                <p class="text-muted">
                                    Наглядные отчеты о развитии навыков, достижениях и участии в соревнованиях каждого ребенка
                                </p>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg mt-4">
                        Подробнее о возможностях
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!-- Отзывы -->
    <section id="testimonials" class="py-5 bg-white">
        <div class="container">
            <!-- Заголовок -->
            <h2 class="text-center mb-5">Что говорят наши пользователи</h2>

            <!-- Сетка карточек -->
            <div class="row row-cols-1 row-cols-md-3 g-4">

                <!-- Первая карточка -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="card-text mb-4">
                                «С помощью этого сайта мы легко зарегистрировали команду, назначили тренировки и уже участвуем в турнирах. Отличный сервис!»
                            </p>
                            <h5 class="card-title mb-5">Анна Петрова</h5>
                            <p class="card-subtitle text-muted small">Тренер команды “Юные чемпионы”</p>
                        </div>
                    </div>
                </div>

                <!-- Вторая карточка -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="card-text mb-4">
                                «Очень удобно отслеживать расписание тренировок и результаты матчей. Дети в восторге!»
                            </p>
                            <h5 class="card-title mb-5">Максим Иванов</h5>
                            <p class="card-subtitle text-muted small">Родитель игрока</p>
                        </div>
                    </div>
                </div>

                <!-- Третья карточка -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="card-text mb-4">
                                «Удобный интерфейс и быстрый отклик поддержки. Рекомендую всем футбольным клубам!»
                            </p>
                            <h5 class="card-title mb-5">Екатерина Смирнова</h5>
                            <p class="card-subtitle text-muted small">Администратор “Лига Стар”</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Призыв к действию -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <!-- Заголовок -->
            <h2 class="display-5 mb-3">
                Присоединяйтесь к нашей спортивной платформе
            </h2>
            <!-- Описание -->
            <p class="lead mb-4">
                Зарегистрируйте вашу детскую команду, добавьте игроков и начните пользоваться всеми преимуществами уже сегодня!
            </p>
            <!-- Кнопка -->
            <a href="/team/create" class="btn btn-light btn-lg">
                Создать профиль команды
            </a>
        </div>
    </section>

@endsection

@section('scripts')
    <!-- Подключите библиотеку animate.css или собственные анимации для класса fanimate -->
@endsection
