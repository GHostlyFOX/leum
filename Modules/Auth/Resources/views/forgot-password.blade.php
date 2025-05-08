@extends('auth::layouts.custom-app')

@section('styles')

@endsection

@section('body')

    <body class="ltr login-img">

    @endsection

    @section('content')
        <div class="page">
            <div>
                <div class="col mx-auto text-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="">
                    </a>
                </div>
                <div class="col-12 container-login100">
                    <div class="row">
                        <div class="col col-login mx-auto">
                            <form class="card shadow-none" method="POST" action="{{ route('password.forgot') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="text-center">
                                        <span class="login100-form-title">Восстановление пароля</span>
                                        <p class="text-muted">Укажите E-mail или номер телефона</p>
                                    </div>
                                    <div class="pt-3">
                                        <div class="form-group">
                                            <label class="form-label">Email или Телефон:</label>
                                            <input class="form-control" name="login" placeholder="Введите E-Mail или номер телефона" required>
                                        </div>
                                        <div class="submit mt-3">
                                            <button type="submit" class="btn btn-primary d-grid">Отправить</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('scripts')

@endsection
