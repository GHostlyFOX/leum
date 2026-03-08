@extends('layouts.landing-app')

@section('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
@endsection

@section('content')

    {{-- ═══════════════════════════════════════════════════════════
         ВОЗМОЖНОСТИ ПЛАТФОРМЫ
    ═══════════════════════════════════════════════════════════ --}}
    <section id="features" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Возможности</span>
                <h2 class="display-5 fw-bold mb-3">Всё для управления детским спортом</h2>
                <p class="lead text-muted mx-auto" style="max-width: 680px;">
                    Полный набор инструментов для клубов, команд, тренировок, матчей и турниров — от регистрации до аналитики.
                </p>
            </div>

            <div class="row g-4">
                {{-- 1 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap bg-primary bg-opacity-10 text-primary">
                            <i class="fe fe-users"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Клубы и команды</h5>
                        <p class="text-muted mb-0">
                            Создавайте клубы, формируйте команды, назначайте тренеров и добавляйте игроков. Управляйте составом и иерархией из одного интерфейса.
                        </p>
                    </div>
                </div>
                {{-- 2 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap bg-info bg-opacity-10 text-info">
                            <i class="fe fe-calendar"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Расписание тренировок</h5>
                        <p class="text-muted mb-0">
                            Планируйте тренировки с привязкой к площадкам и времени. Отмечайте посещаемость и формируйте журнал занятий по каждому игроку.
                        </p>
                    </div>
                </div>
                {{-- 3 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap bg-warning bg-opacity-10 text-warning">
                            <i class="fe fe-award"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Турниры и матчи</h5>
                        <p class="text-muted mb-0">
                            Организуйте соревнования, формируйте заявки команд, ведите протоколы матчей — голы, замены, карточки — всё фиксируется в реальном времени.
                        </p>
                    </div>
                </div>
                {{-- 4 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap bg-success bg-opacity-10 text-success">
                            <i class="fe fe-bar-chart-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Статистика и аналитика</h5>
                        <p class="text-muted mb-0">
                            Наглядные графики посещаемости, результатов и прогресса. Сравнивайте показатели игроков и команд за любой период.
                        </p>
                    </div>
                </div>
                {{-- 5 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap bg-danger bg-opacity-10 text-danger">
                            <i class="fe fe-shield"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Разграничение доступа</h5>
                        <p class="text-muted mb-0">
                            Гибкая система ролей: администратор, тренер, родитель, игрок. Каждый видит только нужную информацию и имеет свои права.
                        </p>
                    </div>
                </div>
                {{-- 6 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap bg-purple bg-opacity-10" style="color: #7c3aed;">
                            <i class="fe fe-bell"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Уведомления</h5>
                        <p class="text-muted mb-0">
                            Автоматические оповещения о тренировках, матчах и изменениях в расписании. Родители всегда в курсе событий.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         ГРАФИКИ И СТАТИСТИКА
    ═══════════════════════════════════════════════════════════ --}}
    <section id="stats" class="py-5 stats-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Аналитика</span>
                <h2 class="display-5 fw-bold mb-3">Наглядная статистика</h2>
                <p class="lead text-muted mx-auto" style="max-width: 640px;">
                    Отслеживайте динамику развития клуба и прогресс каждого игрока с помощью интерактивных графиков.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="chart-card">
                        <h5>Посещаемость тренировок</h5>
                        <canvas id="chartAttendance" height="220"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-card">
                        <h5>Результаты матчей</h5>
                        <canvas id="chartResults" height="220"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-card">
                        <h5>Распределение по видам спорта</h5>
                        <canvas id="chartSports" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         ДЛЯ КОГО ПЛАТФОРМА (роли и их возможности)
    ═══════════════════════════════════════════════════════════ --}}
    <section id="audience" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Для кого</span>
                <h2 class="display-5 fw-bold mb-3">Каждому — свои инструменты</h2>
                <p class="lead text-muted mx-auto" style="max-width: 680px;">
                    Платформа подстраивается под вашу роль: тренер получает инструменты управления, родитель — мониторинг, игрок — свой прогресс.
                </p>
            </div>

            <div class="row g-4">
                {{-- Администратор --}}
                <div class="col-md-6 col-xl-3">
                    <div class="role-card">
                        <div class="role-header" style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
                            <div class="role-icon bg-danger text-white">
                                <i class="fe fe-settings"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Администратор</h5>
                            <small class="text-muted">Полный контроль над платформой</small>
                        </div>
                        <div class="role-body">
                            <ul class="list-unstyled mb-0 mt-3">
                                <li><span class="check-icon">&#10003;</span> Управление клубами и площадками</li>
                                <li><span class="check-icon">&#10003;</span> Создание и настройка турниров</li>
                                <li><span class="check-icon">&#10003;</span> Назначение ролей пользователям</li>
                                <li><span class="check-icon">&#10003;</span> Доступ ко всей статистике</li>
                                <li><span class="check-icon">&#10003;</span> Модерация контента</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Тренер --}}
                <div class="col-md-6 col-xl-3">
                    <div class="role-card">
                        <div class="role-header" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                            <div class="role-icon bg-success text-white">
                                <i class="fe fe-clipboard"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Тренер</h5>
                            <small class="text-muted">Управляет тренировочным процессом</small>
                        </div>
                        <div class="role-body">
                            <ul class="list-unstyled mb-0 mt-3">
                                <li><span class="check-icon">&#10003;</span> Планирование тренировок</li>
                                <li><span class="check-icon">&#10003;</span> Отметка посещаемости</li>
                                <li><span class="check-icon">&#10003;</span> Составы на матчи</li>
                                <li><span class="check-icon">&#10003;</span> Ведение протоколов игр</li>
                                <li><span class="check-icon">&#10003;</span> Анализ прогресса игроков</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Родитель --}}
                <div class="col-md-6 col-xl-3">
                    <div class="role-card">
                        <div class="role-header" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                            <div class="role-icon bg-primary text-white">
                                <i class="fe fe-heart"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Родитель</h5>
                            <small class="text-muted">Следит за ребёнком</small>
                        </div>
                        <div class="role-body">
                            <ul class="list-unstyled mb-0 mt-3">
                                <li><span class="check-icon">&#10003;</span> Расписание тренировок и матчей</li>
                                <li><span class="check-icon">&#10003;</span> Посещаемость ребёнка</li>
                                <li><span class="check-icon">&#10003;</span> Результаты соревнований</li>
                                <li><span class="check-icon">&#10003;</span> Уведомления об изменениях</li>
                                <li><span class="check-icon">&#10003;</span> Связь с тренером</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Игрок --}}
                <div class="col-md-6 col-xl-3">
                    <div class="role-card">
                        <div class="role-header" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                            <div class="role-icon bg-warning text-white">
                                <i class="fe fe-star"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Игрок</h5>
                            <small class="text-muted">Свой профиль и достижения</small>
                        </div>
                        <div class="role-body">
                            <ul class="list-unstyled mb-0 mt-3">
                                <li><span class="check-icon">&#10003;</span> Личный профиль и статистика</li>
                                <li><span class="check-icon">&#10003;</span> Расписание моих тренировок</li>
                                <li><span class="check-icon">&#10003;</span> Результаты моих матчей</li>
                                <li><span class="check-icon">&#10003;</span> Достижения и прогресс</li>
                                <li><span class="check-icon">&#10003;</span> Информация о команде</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         КАК ЭТО РАБОТАЕТ (шаги)
    ═══════════════════════════════════════════════════════════ --}}
    <section id="advantages" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Как это работает</span>
                <h2 class="display-5 fw-bold mb-3">Начните за 4 простых шага</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: 700;">1</div>
                        <h5 class="fw-bold">Регистрация</h5>
                        <p class="text-muted">Создайте аккаунт — это бесплатно и занимает меньше минуты.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: 700;">2</div>
                        <h5 class="fw-bold">Создайте клуб</h5>
                        <p class="text-muted">Укажите вид спорта, город и добавьте логотип клуба.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: 700;">3</div>
                        <h5 class="fw-bold">Добавьте команды</h5>
                        <p class="text-muted">Сформируйте составы, пригласите тренеров и игроков.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: 700;">4</div>
                        <h5 class="fw-bold">Управляйте!</h5>
                        <p class="text-muted">Планируйте тренировки, проводите матчи, следите за прогрессом.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         ОТЗЫВЫ
    ═══════════════════════════════════════════════════════════ --}}
    <section id="testimonials" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Отзывы</span>
                <h2 class="display-5 fw-bold mb-3">Что говорят наши пользователи</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card-new">
                        <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="mb-4 text-muted">
                            &laquo;Мы перевели весь учёт в одно место. Родители довольны — видят расписание и результаты без лишних звонков. Рекомендую!&raquo;
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 44px; height: 44px; color: var(--bs-primary); font-weight: 700;">АП</div>
                            <div>
                                <strong>Анна Петрова</strong>
                                <div class="text-muted small">Тренер, «Юные чемпионы»</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card-new">
                        <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="mb-4 text-muted">
                            &laquo;Наконец-то я вижу, когда у сына тренировка, на какой площадке, и какой был счёт в последнем матче. Всё в телефоне!&raquo;
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 44px; height: 44px; color: #0ea5e9; font-weight: 700;">МИ</div>
                            <div>
                                <strong>Максим Иванов</strong>
                                <div class="text-muted small">Родитель игрока</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card-new">
                        <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="mb-4 text-muted">
                            &laquo;Управляем 8 командами в клубе. Турнирный модуль — находка: заявки, протоколы, расписание — всё автоматизировано.&raquo;
                        </p>
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 44px; height: 44px; color: #ef4444; font-weight: 700;">ЕС</div>
                            <div>
                                <strong>Екатерина Смирнова</strong>
                                <div class="text-muted small">Администратор, «Лига Стар»</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         ПРИЗЫВ К ДЕЙСТВИЮ
    ═══════════════════════════════════════════════════════════ --}}
    <section id="cta" class="cta-section py-5 text-white text-center">
        <div class="container position-relative">
            <h2 class="display-5 fw-bold mb-3">
                Присоединяйтесь к «Детской лиге»
            </h2>
            <p class="lead mb-4 opacity-75 mx-auto" style="max-width: 600px;">
                Зарегистрируйте ваш клуб, добавьте команды и начните использовать все возможности платформы уже сегодня — бесплатно.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('auth.register') }}" class="btn btn-light btn-lg px-5">
                    Создать аккаунт
                </a>
                <a href="{{ route('auth.loginForm') }}" class="btn btn-outline-light btn-lg px-5">
                    Войти
                </a>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var green  = '#8fbd56';
    var greenL = 'rgba(143,189,86,0.2)';
    var blue   = '#3b82f6';
    var amber  = '#f59e0b';

    // ── Посещаемость ──────────────────────────
    new Chart(document.getElementById('chartAttendance'), {
        type: 'line',
        data: {
            labels: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
            datasets: [{
                label: 'Посещаемость, %',
                data: [72, 68, 75, 80, 85, 78, 60, 55, 82, 88, 90, 87],
                borderColor: green,
                backgroundColor: greenL,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, max: 100, ticks: { callback: function(v){return v+'%';} } } }
        }
    });

    // ── Результаты матчей ─────────────────────
    new Chart(document.getElementById('chartResults'), {
        type: 'bar',
        data: {
            labels: ['Янв','Фев','Мар','Апр','Май','Июн'],
            datasets: [
                { label: 'Победы',    data: [5,7,6,9,8,11], backgroundColor: green,    borderRadius: 4 },
                { label: 'Ничьи',     data: [2,1,3,2,1,2],  backgroundColor: amber,    borderRadius: 4 },
                { label: 'Поражения', data: [3,2,1,1,2,0],  backgroundColor: '#ef4444', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } },
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
        }
    });

    // ── Виды спорта ───────────────────────────
    new Chart(document.getElementById('chartSports'), {
        type: 'doughnut',
        data: {
            labels: ['Футбол','Баскетбол','Хоккей','Волейбол','Плавание','Другие'],
            datasets: [{
                data: [35, 20, 15, 12, 10, 8],
                backgroundColor: [green, blue, '#ef4444', amber, '#8b5cf6', '#6b7280'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } }
            }
        }
    });
});
</script>
@endsection
