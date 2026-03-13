<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Training\Models\Training;

#[Layout('layouts.app')]
class TrainingDetail extends Component
{
    public ?int $trainingId = null;
    public ?Training $training = null;
    public array $attendance = [];
    public bool $canEdit = false;

    public function mount(int $id)
    {
        $this->trainingId = $id;
        $this->loadTraining();
    }

    public function loadTraining()
    {
        $this->training = Training::with([
            'team.club',
            'coach',
            'venue',
            'attendance.user',
            'trainingType',
        ])->findOrFail($this->trainingId);

        // Проверка прав на редактирование
        $user = Auth::user();
        $this->canEdit = $user->id === $this->training->coach_id || 
            $user->hasPermission('trainings.update');

        // Загружаем посещаемость
        $this->attendance = $this->training->attendance
            ->map(fn($a) => [
                'id' => $a->id,
                'player_id' => $a->player_user_id,
                'name' => $a->user->full_name,
                'status' => $a->status,
                'notes' => $a->notes,
            ])
            ->toArray();
    }

    public function updateAttendance(int $attendanceId, string $status)
    {
        if (!$this->canEdit) {
            $this->dispatch('notify', type: 'error', message: 'Нет прав для редактирования');
            return;
        }

        $attendance = $this->training->attendance()->find($attendanceId);
        if ($attendance) {
            $attendance->update(['status' => $status]);
            $this->loadTraining();
            $this->dispatch('notify', type: 'success', message: 'Посещаемость обновлена');
        }
    }

    public function render()
    {
        return view('livewire.training-detail');
    }
}
