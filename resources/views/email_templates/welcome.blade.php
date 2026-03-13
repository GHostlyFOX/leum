@extends('email_templates.layout')

@section('title', 'Добро пожаловать в Сбор!')

@section('header', 'Добро пожаловать!')

@section('content')
<h2 style="color: #2d4a14; margin-top: 0;">Здравствуйте, {{ $userName }}!</h2>

<p>Благодарим за регистрацию на платформе <strong>Сбор</strong> — спортивной платформе для управления клубами, командами и тренировками.</p>

<div class="info-box">
    <h3 style="margin-top: 0; color: #2d4a14;">Ваши данные для входа:</h3>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p style="margin-bottom: 0;">Используйте указанный email и пароль для входа в систему.</p>
</div>

<p>С помощью Сбора вы сможете:</p>
<ul style="line-height: 2;">
    <li>Управлять составом команды и тренерским штабом</li>
    <li>Планировать тренировки и отслеживать посещаемость</li>
    <li>Организовывать матчи и турниры</li>
    <li>Общаться с игроками и родителями</li>
    <li>Вести статистику и аналитику</li>
</ul>

<div style="text-align: center;">
    <a href="{{ $dashboardLink }}" class="btn">Перейти в личный кабинет</a>
</div>

<div class="divider"></div>

<p style="font-size: 14px; color: #6c757d;">
    Если у вас возникнут вопросы, обратитесь в службу поддержки по адресу <a href="mailto:support@sbor.team">support@sbor.team</a>
</p>
@endsection
