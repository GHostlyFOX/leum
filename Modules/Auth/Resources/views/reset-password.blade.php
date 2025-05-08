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
                <div class="col mx-auto text-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="">
                    </a>
                </div>
                <div class="col-12 container-login100">
                    <div class="row">
                        <div class="col col-login mx-auto">
                            <form class="card shadow-none" method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="card-body">
                                    <div class="text-center">
                                        <span class="login100-form-title">Сброс пароля</span>
                                        <p class="text-muted">Введите новый пароль</p>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label class="form-label">Новый пароль</label>
                                        <input class="form-control" type="password" name="password" required>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label class="form-label">Подтверждение пароля</label>
                                        <input class="form-control" type="password" name="password_confirmation" required>
                                    </div>

                                    <div class="submit mt-3">
                                        <button type="submit" class="btn btn-primary d-grid">Сохранить пароль</button>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="text-center pt-3">
                                        <p class="text-dark mb-0">
                                            Вспомнили пароль? <a href="{{ route('login') }}" class="text-primary ms-1">Войти</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE -->

@endsection

@section('scripts')

@endsection
