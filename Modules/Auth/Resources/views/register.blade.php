@extends('auth::layouts.custom-app')

@section('styles')

@endsection

@section('body')

    <body class="ltr login-img">

    @endsection

    @section('content')
        <!-- PAGE -->
        <div class="page">
            <div>
                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto text-center">
                    <a href="{{url('index')}}">
                        <img src="{{asset('assets/images/brand/logo.png')}}" class="header-brand-img" alt="">
                    </a>
                </div>
                <div class="container-login100">
                    <div class="wrap-login100 p-0">
                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}" class="login100-form validate-form">
                                @csrf

                                <span class="login100-form-title">
        Регистрация
    </span>

                                <!-- Имя -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Введите имя">
                                    <input class="input100" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Имя *" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="mdi mdi-account-outline" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Фамилия -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Введите фамилию">
                                    <input class="input100" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Фамилия *" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="mdi mdi-account" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Отчество -->
                                <div class="wrap-input100">
                                    <input class="input100" type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Отчество">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="mdi mdi-account-circle-outline" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Email -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Укажите корректный email">
                                    <input class="input100" type="email" name="email" value="{{ old('email') }}" placeholder="E-Mail">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="zmdi zmdi-email" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Телефон -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Формат: +7XXXXXXXXXX">
                                    <input class="input100" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Телефон *" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="mdi mdi-phone" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Дата рождения -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Укажите дату рождения">
                                    <input class="input100" type="date" name="birth_date" value="{{ old('birth_date') }}" placeholder="Дата рождения *" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="mdi mdi-calendar" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Пол -->
                                <div class="wrap-input100 validate-input">
                                    <select class="input100" name="gender" required>
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Пол *</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Мужской</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Женский</option>
                                    </select>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="mdi mdi-gender-male-female" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Пароль -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Введите пароль">
                                    <input class="input100" type="password" name="password" placeholder="Пароль *" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="zmdi zmdi-lock" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Подтверждение пароля -->
                                <div class="wrap-input100 validate-input" data-bs-validate="Подтвердите пароль">
                                    <input class="input100" type="password" name="password_confirmation" placeholder="Подтверждение пароля *" required>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
            <i class="zmdi zmdi-lock-outline" aria-hidden="true"></i>
        </span>
                                </div>

                                <!-- Согласие на обработку данных -->
                                <label class="custom-control custom-checkbox mt-4">
                                    <input type="checkbox" class="custom-control-input" name="consent_personal_data" required>
                                    <span class="custom-control-label">
            Я согласен с <a href="{{ route('personal.data.agreement') }}" target="_blank">условиями обработки персональных данных</a> *
        </span>
                                </label>

                                <!-- Согласие на уведомления -->
                                <label class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" name="notifications_on" {{ old('notifications_on') ? 'checked' : '' }}>
                                    <span class="custom-control-label">
            Хочу получать уведомления и рассылки
        </span>
                                </label>

                                <!-- Кнопка регистрации -->
                                <div class="container-login100-form-btn mt-4">
                                    <button type="submit" class="login100-form-btn btn-primary">
                                        Зарегистрироваться
                                    </button>
                                </div>

                                <div class="text-center pt-3">
                                    <p class="text-dark mb-0">
                                        Уже есть аккаунт?
                                        <a href="{{ route('auth.index') }}" class="text-primary ms-1">Войти</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- END PAGE -->

@endsection

@section('scripts')

@endsection
