@extends('email_templates.layout')

@section('title', 'Приглашение в команду - Сбор')

@section('header', 'Вас пригласили!')

@section('content')
<h2 style="color: #2d4a14; margin-top: 0;">Здравствуйте{{ $userName ? ', ' . $userName : '' }}!</h2>

<p>Вы получили приглашение присоединиться к команде на платформе <strong>Сбор</strong>.</p>

<div class="info-box">
    <h3 style="margin-top: 0; color: #2d4a14;">Информация о приглашении:</h3>
    <p><strong>Клуб:</strong> {{ $clubName }}</p>
    <p><strong>Команда:</strong> {{ $teamName }}</p>
    <p><strong>Роль:</strong> {{ $roleName }}</p>
    @if($invitedBy)
        <p><strong>Пригласил:</strong> {{ $invitedBy }}</p>
    @endif
</div>

<p>Для принятия приглашения и входа в команду, нажмите на кнопку ниже:</p>

<div style="text-align: center;">
    <a href="{{ $inviteLink }}" class="btn">Принять приглашение</a>
</div>

<div class="alert-box">
    <strong>Важно:</strong> Ссылка действительна {{ $expiresIn ?? 'в течение 7 дней' }}. 
    Если вы не ожидали это приглашение, просто проигнорируйте это письмо.
</div>

<div class="divider"></div>

<p style="font-size: 14px; color: #6c757d;">
    Если кнопка не работает, скопируйте и вставьте эту ссылку в браузер:<br>
    <code style="background-color: #f5f5f5; padding: 4px 8px; border-radius: 4px; word-break: break-all;">{{ $inviteLink }}</code>
</p>
@endsection
