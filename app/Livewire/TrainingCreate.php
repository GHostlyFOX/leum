<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Training;
use Modules\Training\Models\Venue;
use Modules\Training\Models\RefTrainingType;

#[Layout('layouts.app')]
class TrainingCreate extends Component
{
    public ?int $clubId = null;
    public ?int $sportTypeId = null;
    public ?int $teamId = null;
    public array $teams = [];
    public array $venues = [];
    public array $trainingTypes = [];

    // Form fields
    public string $trainingDate = '';
    public string $startTime = '';
    public int $durationMinutes = 90;
    public ?int $selectedTeamId = null;
    public ?int $selectedVenueId = null;
    public ?int $selectedTrainingTypeId = null;
    public ?int $selectedCoachId = null;
    public string $comment = '';
    public bool $notifyParents = true;
    public bool $requireRsvp = true;

    // Venue modal fields
    public bool $showVenueModal = false;
    public string $venueName = '';
    public string $venueAddress = '';

    public function mount(): void
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8]) // admin or coach
            ->first();

        if (!$membership) {
            redirect()->route('home')->with('error', 'Нет доступа');
            return;
        }

        $this->clubId = $membership->club_id;

        $club = Club::find($this->clubId);
        $this->sportTypeId = $club?->sport_type_id;

        $this->loadTeams();
        $this->loadVenues();
        $this->loadTrainingTypes();

        // Default values
        $this->trainingDate = now()->format('Y-m-d');
        $this->startTime = '18:00';

        if ($this->teamId) {
            $this->selectedTeamId = $this->teamId;
        } elseif (count($this->teams) === 1) {
            $this->selectedTeamId = $this->teams[0]['id'];
        }
    }

    // ── Venue modal ─────────────────────────────────────────────────

    public function openVenueModal(): void
    {
        $this->venueName = '';
        $this->venueAddress = '';
        $this->showVenueModal = true;
    }

    public function closeVenueModal(): void
    {
        $this->showVenueModal = false;
        $this->resetErrorBag(['venueName', 'venueAddress']);
    }

    public function createVenue(): void
    {
        $this->validate([
            'venueName' => 'required|string|max:255',
            'venueAddress' => 'required|string|max:500',
        ], [
            'venueName.required' => 'Введите название площадки',
            'venueAddress.required' => 'Введите адрес',
        ]);

        // Получаем city_id и country_id клуба
        $club = Club::find($this->clubId);

        $venue = Venue::create([
            'name' => $this->venueName,
            'address' => $this->venueAddress,
            'club_id' => $this->clubId,
            'country_id' => $club?->country_id ?? 1,
            'city_id' => $club?->city_id ?? 1,
        ]);

        // Обновляем список площадок
        $this->loadVenues();

        // Выбираем только что созданную площадку
        $this->selectedVenueId = $venue->id;

        $this->closeVenueModal();
        $this->dispatch('notify', type: 'success', message: 'Площадка добавлена');
    }

    // ── Save training ───────────────────────────────────────────────

    public function save()
    {
        $this->validate([
            'selectedTeamId' => 'required|exists:teams,id',
            'trainingDate' => 'required|date',
            'startTime' => 'required',
            'durationMinutes' => 'required|integer|min:15|max:300',
            'selectedVenueId' => 'nullable|exists:venues,id',
            'selectedTrainingTypeId' => 'nullable|exists:ref_training_types,id',
        ], [
            'selectedTeamId.required' => 'Выберите команду',
            'trainingDate.required' => 'Укажите дату',
            'startTime.required' => 'Укажите время',
            'durationMinutes.required' => 'Укажите длительность',
        ]);

        // Check team belongs to club
        $team = Team::find($this->selectedTeamId);
        if (!$team || $team->club_id !== $this->clubId) {
            $this->dispatch('notify', type: 'error', message: 'Команда не найдена');
            return;
        }

        $training = Training::create([
            'club_id' => $this->clubId,
            'team_id' => $this->selectedTeamId,
            'coach_id' => Auth::id(),
            'training_date' => $this->trainingDate,
            'start_time' => $this->startTime,
            'duration_minutes' => $this->durationMinutes,
            'venue_id' => $this->selectedVenueId,
            'training_type_id' => $this->selectedTrainingTypeId,
            'comment' => $this->comment,
            'notify_parents' => $this->notifyParents,
            'require_rsvp' => $this->requireRsvp,
            'status' => 'scheduled',
        ]);

        $this->dispatch('notify', type: 'success', message: 'Тренировка создана');
        return redirect()->route('club.team.show', $this->selectedTeamId);
    }

    // ── Render ───────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.training-create', [
            'coaches' => TeamMember::where('club_id', $this->clubId)
                ->whereIn('role_id', [8, 11]) // coach or assistant
                ->with('user')
                ->get(),
        ]);
    }

    // ── Private helpers ─────────────────────────────────────────────

    private function loadTeams(): void
    {
        $this->teams = Team::where('club_id', $this->clubId)
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();
    }

    private function loadVenues(): void
    {
        $this->venues = Venue::where('club_id', $this->clubId)
            ->orWhereNull('club_id')
            ->orderBy('name')
            ->get()
            ->map(fn($v) => ['id' => $v->id, 'name' => $v->name])
            ->toArray();
    }

    private function loadTrainingTypes(): void
    {
        // Глобальные типы для вида спорта клуба + общие + клубские
        $this->trainingTypes = RefTrainingType::where(function ($q) {
                // Глобальные типы для вида спорта клуба
                $q->whereNull('club_id')
                  ->where(function ($sub) {
                      $sub->where('sport_type_id', $this->sportTypeId)
                          ->orWhereNull('sport_type_id'); // общие для всех видов спорта
                  });
            })
            ->orWhere('club_id', $this->clubId) // клубские типы
            ->orderBy('name')
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();
    }
}
