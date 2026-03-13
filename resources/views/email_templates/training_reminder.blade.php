@extends('email_templates.layout')

@section('title', 'Напоминание о тренировке')

@section('header', 'Напоминание о предстоящей тренировке')

@section('content')
<h2 style="color: #2d4a14;">Здравствуйте, {{ $parentName }}!</h2>

<p>Напоминаем, что завтра состоится тренировка:</p>

<div style="background: #f0fdf4; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3 style="margin: 0 0 12px 0; color: #1f2937;">{{ $teamName }}</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; color: #6b7280; width: 120px;">Дата:</td>
            <td style="padding: 8px 0; color: #1f2937; font-weight: 600;">{{ $training->training_date->format('d.m.Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #6b7280;">Время:</td>
            <td style="padding: 8px 0; color: #1f2937; font-weight: 600;">{{ $training->start_time->format('H:i') }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #6b7280;">Место:</td>
            <td style="padding: 8px 0; color: #1f2937; font-weight: 600;">{{ $training->venue?->name ?? 'Не указано' }}</td>
        </tr>
    </table>
</div>

@if($training->require_rsvp)
<p style="background: #fef3c7; padding: 12px; border-radius: 8px; color: #92400e;">
    <strong>Важно:</strong> Пожалуйста, подтвердите присутствие вашего ребенка в личном кабинете.
</p>
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ route('home') }}" 
       style="display: inline-block; padding: 14px 28px; background: #8fbd56; color: #fff; text-decoration: none; border-radius: 10px; font-weight: 600;">
        Перейти в личный кабинет
    </a>
</div>

<p style="color: #6b7280; font-size: 14px;">
    Вы получили это письмо, потому что указаны как родитель игрока в команде {{ $teamName }}.
    Если вы хотите отключить уведомления, измените настройки в <a href="{{ route('home') }}" style="color: #8fbd56;">личном кабинете</a>.
</p>
@endsection
