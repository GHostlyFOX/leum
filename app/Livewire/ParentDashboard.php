<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\EventResponse;
use Modules\Training\Models\Training;
use Modules\User\Models\UserParentPlayer;

#[Layout('layouts.app')]
class ParentDashboard extends Component
{
    public array $children = [];
    public ?int $selectedChildId = null;

    public function mount()
    {
        $user = Auth::user();
        
        // Получаем детей родителя
        $childrenIds = UserParentPlayer::where('parent_user_id', $user->id)
            ->pluck('player_user_id')
            ->toArray();

        if (empty($childrenIds)) {
            return;
        }

        // Загружаем информацию о детях
        $this->children = TeamMember::whereIn('user_id', $childrenIds)
            ->where('is_active', true)
            ->with(['user.playerProfile.position', 'team.club'])
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->user_id,
                    'name' => $member->user->full_name,
                    'photo' => $member->user->photo_file_id,
                    'team' => $member->team->name,
                    'club' => $member->team->club->name,
                    'position' => $member->user->playerProfile?->position?->name,
                    'birth_date' => $member->user->birth_date?->format('d.m.Y'),
                    'team_id' => $member->team_id,
                ];
            })
            ->toArray();

        if (!empty($this->children)) {
            $this->selectedChildId = $this->children[0]['id'];
        }
    }

    public function selectChild(int $childId)
    {
        $this->selectedChildId = $childId;
    }

    public function render()
    {
        $selectedChild = null;
        $upcomingTrainings = [];
        $upcomingMatches = [];
        $attendanceStats = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'percentage' => 0,
        ];

        if ($this->selectedChildId) {
            $selectedChild = collect($this->children)->firstWhere('id', $this->selectedChildId);
            
            // Предстоящие тренировки
            $upcomingTrainings = Training::whereHas('attendance', function ($q) {
                $q->where('player_user_id', $this->selectedChildId);
            })
            ->where('training_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('training_date')
            ->orderBy('start_time')
            ->limit(5)
            ->with('venue')
            ->get()
            ->map(function ($training) {
                $response = EventResponse::where('event_type', 'training')
                    ->where('event_id', $training->id)
                    ->where('user_id', $this->selectedChildId)
                    ->first();

                return [
                    'id' => $training->id,
                    'date' => $training->training_date->format('d.m.Y'),
                    'time' => $training->start_time->format('H:i'),
                    'venue' => $training->venue?->name,
                    'status' => $response?->status ?? 'pending',
                ];
            })
            ->toArray();

            // Статистика посещаемости за последние 30 дней
            $attendanceData = \DB::table('training_attendance')
                ->where('player_user_id', $this->selectedChildId)
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $attendanceStats = [
                'total' => array_sum($attendanceData),
                'present' => $attendanceData['present'] ?? 0,
                'absent' => $attendanceData['absent'] ?? 0,
                'percentage' => isset($attendanceData['present'], $attendanceData['absent']) 
                    ? round(($attendanceData['present'] / ($attendanceData['present'] + $attendanceData['absent'])) * 100)
                    : 0,
            ];
        }

        return view('livewire.parent-dashboard', [
            'selectedChild' => $selectedChild,
            'upcomingTrainings' => $upcomingTrainings,
            'upcomingMatches' => $upcomingMatches,
            'attendanceStats' => $attendanceStats,
        ]);
    }
}
