@extends('auth::layouts.custom-app')

@section('styles')

@endsection

@section('body')

    <body class="ltr login-img">

    @endsection

    @section('content')
        <div class="page">
            <div>
                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto text-center">
                    <a href="{{ url('/') }}" class="text-center">
                        <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="Logo">
                    </a>
                </div>
                <div class="container-login100">
                    <div class="wrap-login100 p-0">
                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                                @csrf

                                <span class="login100-form-title">
                            Вход
                        </span>

                                <!-- Email или Телефон -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Введите email или телефон">
                                    <input class="input100" type="text" name="login" value="{{ old('login') }}" placeholder="Email или телефон" required autofocus>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                <i class="zmdi zmdi-account" aria-hidden="true"></i>
                            </span>
                                </div>

                                <!-- Пароль -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Введите пароль">
                                    <input class="input100" type="password" name="password" placeholder="Пароль" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>
                                </div>

                                <!-- Запомнить меня -->
                                <div class="form-check mb-3 text-start">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Запомнить меня
                                    </label>
                                </div>

                                <!-- Ссылка на восстановление пароля -->
                                <div class="text-end pt-1">
                                    <p class="mb-0"><a href="{{ route('password.request') }}" class="text-primary ms-1">Забыли пароль?</a></p>
                                </div>

                                <!-- Кнопка входа -->
                                <div class="container-login100-form-btn">
                                    <button type="submit" class="login100-form-btn btn-primary">
                                        Войти
                                    </button>
                                </div>

                                <!-- Ссылка на регистрацию -->
                                <div class="text-center pt-3">
                                    <p class="text-dark mb-0">Нет аккаунта? <a href="{{ route('register') }}" class="text-primary ms-1">Зарегистрироваться</a></p>
                                </div>
                            </form>
                        </div>

                        <!-- Соц. входы -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-center my-3">
                                <a href="javascript:void(0)" class="social-login text-center me-4">
                                    <i class="fa fa-google"></i>
                                </a>
                                <a href="javascript:void(0)" class="social-login text-center me-4">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a href="javascript:void(0)" class="social-login text-center">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>

@endsection

@section('scripts')

@endsection
