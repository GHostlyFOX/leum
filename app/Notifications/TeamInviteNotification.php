<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Team\Models\Team;

class TeamInviteNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Team $team,
        protected string $role,
        protected string $joinUrl,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleLabel = match ($this->role) {
            'coach'  => 'тренера',
            'parent' => 'родителя',
            default  => 'игрока',
        };

        $clubName = $this->team->club?->name ?? '';
        $teamName = $this->team->name;

        $subject = "Приглашение в команду «{$teamName}»";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Здравствуйте!')
            ->line("Вас пригласили в команду «{$teamName}»" . ($clubName ? " клуба «{$clubName}»" : '') . " в роли {$roleLabel}.")
            ->line('Нажмите на кнопку ниже, чтобы принять приглашение.')
            ->action('Принять приглашение', $this->joinUrl)
            ->line('Если у вас уже есть аккаунт, вы сможете принять приглашение из дашборда. Если нет — вам будет предложено зарегистрироваться.')
            ->salutation('С уважением, команда Сбор');
    }
}
