<!-- Footer -->
<div class="demo-footer">
    <div class="container">
        <div class="card mb-0 px-0 border-0 shadow-none">
            <div class="card-body px-0">
                <div class="top-footer">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                            <h6 class="text-uppercase mb-3 fw-semibold">О платформе</h6>
                            <p class="text-muted">
                                Сбор (SquadUp.ru) — сервис для детского спорта, где важна не только игра, но и вся жизнь команды вокруг неё.
                                Объединяет клуб, тренера, родителей и юных спортсменов в единое цифровое пространство —
                                от расписания тренировок до итогов турнира и личной статистики каждого игрока.
                            </p>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-md-4 mb-4 mb-lg-0">
                            <h6 class="text-uppercase mb-3 fw-semibold">Платформа</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="#features" class="text-muted">Возможности</a></li>
                                <li class="mb-2"><a href="#stats" class="text-muted">Аналитика</a></li>
                                <li class="mb-2"><a href="#audience" class="text-muted">Для кого</a></li>
                                <li class="mb-2"><a href="#advantages" class="text-muted">Как это работает</a></li>
                                <li class="mb-2"><a href="#testimonials" class="text-muted">Отзывы</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-md-4 mb-4 mb-lg-0">
                            <h6 class="text-uppercase mb-3 fw-semibold">Аккаунт</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="{{ route('auth.register') }}" class="text-muted">Регистрация</a></li>
                                <li class="mb-2"><a href="{{ route('auth.loginForm') }}" class="text-muted">Вход</a></li>
                                <li class="mb-2"><a href="{{ route('password.request') }}" class="text-muted">Забыли пароль?</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <h6 class="text-uppercase mb-3 fw-semibold">Контакты</h6>
                            <p class="text-muted mb-2"><i class="fa fa-map-marker me-2"></i> Россия</p>
                            <p class="text-muted mb-2"><i class="fa fa-envelope me-2"></i> info@squadup.ru</p>
                            <p class="text-muted mb-2"><i class="fa fa-globe me-2"></i> squadup.ru</p>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <footer class="main-footer px-0 pb-0 border-0">
                    <div class="row align-items-center">
                        <div class="col-md-8 footer1">
                            &copy; {{ date('Y') }} <a href="{{ url('/') }}">Сбор (SquadUp.ru)</a>. Все права защищены.
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('personal.data.agreement') }}" class="text-muted small">Политика конфиденциальности</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
<!-- /Footer -->
