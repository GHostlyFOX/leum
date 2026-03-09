@section('body')
<body class="ltr login-img">
@endsection

@section('content')

<!-- PAGE -->
<div class="page">
    <div>
        <div class="col col-login mx-auto text-center">
            <a href="{{ url('/') }}" class="text-center">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="Сбор">
            </a>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body">
                    <form class="login100-form validate-form" method="POST" action="{{ route('auth.login') }}">
                        @csrf
                        <span class="login100-form-title">Вход</span>

                        @if(session('status'))
                            <div class="alert alert-success text-center mb-3">{{ session('status') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="wrap-input100 validate-input" data-bs-validate="Введите e-mail или телефон">
                            <input class="input100" type="text" name="login" placeholder="E-Mail или телефон" value="{{ old('login') }}" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-email" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input" data-bs-validate="Введите пароль">
                            <input class="input100" type="password" name="password" placeholder="Пароль" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-1 mb-3">
                            <label class="custom-control custom-checkbox mb-0">
                                <input type="checkbox" class="custom-control-input" name="remember" value="1">
                                <span class="custom-control-label">Запомнить</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-primary">Забыли пароль?</a>
                        </div>

                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Войти
                            </button>
                        </div>

                        <div class="text-center pt-3">
                            <p class="text-dark mb-0">Нет аккаунта? <a href="{{ route('auth.register') }}" class="text-primary ms-1">Зарегистрируйтесь</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End PAGE -->

@endsection
