@extends('email_templates.layout')

@section('title', 'Восстановление пароля - Сбор')

@section('header', 'Восстановление пароля')

@section('content')
<h2 style="color: #2d4a14; margin-top: 0;">Здравствуйте!</h2>

<p>Вы запросили восстановление пароля для аккаунта на платформе <strong>Сбор</strong>.</p>

<div class="info-box">
    <p style="margin: 0;">Для сброса пароля нажмите на кнопку ниже. Ссылка действительна в течение 60 минут.</p>
</div>

<div style="text-align: center;">
    <a href="{{ $resetLink }}" class="btn">Сбросить пароль</a>
</div>

<div class="alert-box">
    <strong>Безопасность:</strong> Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо. 
    Ваш пароль останется неизменным.
</div>

<div class="divider"></div>

<p style="font-size: 14px; color: #6c757d;">
    Если кнопка не работает, скопируйте и вставьте эту ссылку в браузер:<br>
    <code style="background-color: #f5f5f5; padding: 4px 8px; border-radius: 4px; word-break: break-all;">{{ $resetLink }}</code>
</p>
@endsection
