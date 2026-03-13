<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Training;
use Modules\User\Models\User;

class SendEventReminders extends Command
{
    protected $signature = 'send:event-reminders {--hours=24 : За сколько часов до события отправлять напоминание}';
    protected $description = 'Отправка email-напоминаний о предстоящих тренировках и матчах';

    public function handle()
    {
        $hours = (int) $this->option('hours');
        $targetTime = now()->addHours($hours);
        
        $this->info("Отправка напоминаний за {$hours} часов...");

        // Находим тренировки, которые начнутся через указанное время
        $trainings = Training::where('training_date', $targetTime->toDateString())
            ->where('start_time', '<=', $targetTime->toTimeString())
            ->where('start_time', '>=', $targetTime->subHour()->toTimeString())
            ->where('status', 'scheduled')
            ->where('notify_parents', true)
            ->with('team', 'venue')
            ->get();

        $this->info("Найдено тренировок: {$trainings->count()}");

        foreach ($trainings as $training) {
            $this->sendTrainingReminders($training);
        }

        $this->info('Готово!');
        return 0;
    }

    private function sendTrainingReminders(Training $training)
    {
        // Получаем игроков команды
        $players = TeamMember::where('team_id', $training->team_id)
            ->where('role_id', 10) // player
            ->where('is_active', true)
            ->pluck('user_id')
            ->toArray();

        // Получаем родителей
        $parentIds = \DB::table('user_parent_player')
            ->whereIn('player_user_id', $players)
            ->pluck('parent_user_id')
            ->unique()
            ->toArray();

        $parents = User::whereIn('id', $parentIds)
            ->where('notifications_on', true)
            ->get();

        $this->info("Тренировка #{$training->id}: отправка {$parents->count()} родителям");

        foreach ($parents as $parent) {
            try {
                Mail::send('email_templates.training_reminder', [
                    'parentName' => $parent->first_name,
                    'training' => $training,
                    'teamName' => $training->team->name,
                ], function ($message) use ($parent) {
                    $message->to($parent->email, $parent->full_name)
                        ->subject('Напоминание о тренировке');
                });
            } catch (\Exception $e) {
                $this->error("Ошибка отправки для {$parent->email}: {$e->getMessage()}");
            }
        }
    }
}
