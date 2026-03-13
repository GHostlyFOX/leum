<?php

declare(strict_types=1);

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Match\Models\GameMatch;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Training;

#[Layout('layouts.app')]
class TeamCalendar extends Component
{
    public ?int $teamId = null;
    public ?int $clubId = null;
    public Carbon $currentMonth;
    public array $events = [];
    public array $userTeams = [];

    public function mount(?int $teamId = null)
    {
        $this->currentMonth = now();
        
        $user = Auth::user();
        
        // Получаем команды пользователя
        $memberships = TeamMember::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('team')
            ->get();

        $this->userTeams = $memberships->map(fn($m) => [
            'id' => $m->team_id,
            'name' => $m->team->name,
        ])->toArray();

        if ($teamId) {
            $this->teamId = $teamId;
        } elseif (count($this->userTeams) === 1) {
            $this->teamId = $this->userTeams[0]['id'];
        }

        if ($this->teamId) {
            $team = Team::find($this->teamId);
            $this->clubId = $team?->club_id;
            $this->loadEvents();
        }
    }

    public function updatedTeamId()
    {
        if ($this->teamId) {
            $team = Team::find($this->teamId);
            $this->clubId = $team?->club_id;
            $this->loadEvents();
        }
    }

    public function previousMonth()
    {
        $this->currentMonth = $this->currentMonth->copy()->subMonth();
        $this->loadEvents();
    }

    public function nextMonth()
    {
        $this->currentMonth = $this->currentMonth->copy()->addMonth();
        $this->loadEvents();
    }

    public function loadEvents()
    {
        if (!$this->teamId) {
            return;
        }

        $startOfMonth = $this->currentMonth->copy()->startOfMonth();
        $endOfMonth = $this->currentMonth->copy()->endOfMonth();

        // Тренировки
        $trainings = Training::where('team_id', $this->teamId)
            ->whereBetween('training_date', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->with('venue')
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'type' => 'training',
                'title' => 'Тренировка',
                'date' => $t->training_date->format('Y-m-d'),
                'time' => $t->start_time->format('H:i'),
                'venue' => $t->venue?->name,
                'status' => $t->status,
            ]);

        // Матчи
        $matches = GameMatch::where('team_id', $this->teamId)
            ->whereBetween('match_date', [$startOfMonth, $endOfMonth])
            ->with('opponent')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'type' => 'match',
                'title' => 'Матч vs ' . ($m->opponent?->name ?? 'Соперник'),
                'date' => $m->match_date?->format('Y-m-d'),
                'time' => $m->start_time?->format('H:i'),
                'score' => $m->score_home . ':' . $m->score_away,
                'status' => $m->status,
            ]);

        $this->events = $trainings->merge($matches)
            ->sortBy('date')
            ->groupBy('date')
            ->toArray();
    }

    public function render()
    {
        // Генерация дней календаря
        $startOfMonth = $this->currentMonth->copy()->startOfMonth();
        $endOfMonth = $this->currentMonth->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $days = [];
        $current = $startOfCalendar->copy();

        while ($current <= $endOfCalendar) {
            $dateStr = $current->format('Y-m-d');
            $days[] = [
                'date' => $current->copy(),
                'isCurrentMonth' => $current->month === $this->currentMonth->month,
                'isToday' => $current->isToday(),
                'events' => $this->events[$dateStr] ?? [],
            ];
            $current->addDay();
        }

        // Названия месяцев на русском
        $months = [
            'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
        ];

        return view('livewire.team-calendar', [
            'days' => $days,
            'monthName' => $months[$this->currentMonth->month - 1],
            'year' => $this->currentMonth->year,
        ]);
    }
}
