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

    public function mount()
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8]) // admin or coach
            ->first();

        if (!$membership) {
            return redirect()->route('home')->with('error', 'Нет доступа');
        }

        $this->clubId = $membership->club_id;
        
        // Load teams
        $this->teams = Team::where('club_id', $this->clubId)
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();
        
        // Load venues
        $this->venues = Venue::where('club_id', $this->clubId)
            ->orWhereNull('club_id')
            ->get()
            ->map(fn($v) => ['id' => $v->id, 'name' => $v->name])
            ->toArray();
        
        // Load training types
        $this->trainingTypes = RefTrainingType::all()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();

        // Default values
        $this->trainingDate = now()->format('Y-m-d');
        $this->startTime = '18:00';
        
        if ($this->teamId) {
            $this->selectedTeamId = $this->teamId;
        } elseif (count($this->teams) === 1) {
            $this->selectedTeamId = $this->teams[0]['id'];
        }
    }

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
            'status' => 'planned',
        ]);

        $this->dispatch('notify', type: 'success', message: 'Тренировка создана');
        return redirect()->route('club.team.show', $this->selectedTeamId);
    }

    public function render()
    {
        return view('livewire.training-create', [
            'coaches' => TeamMember::where('club_id', $this->clubId)
                ->whereIn('role_id', [8, 11]) // coach or assistant
                ->with('user')
                ->get(),
        ]);
    }
}
