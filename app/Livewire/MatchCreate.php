<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Match\Models\GameMatch;
use Modules\Match\Models\Opponent;
use Modules\Reference\Models\RefSportType;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Tournament\Models\Tournament;
use Modules\Training\Models\Venue;

#[Layout('layouts.app')]
class MatchCreate extends Component
{
    public ?int $clubId = null;
    public array $teams = [];
    public array $venues = [];
    public array $tournaments = [];
    public array $opponents = [];

    // Form fields
    public string $matchType = 'friendly'; // friendly, tournament
    public ?int $selectedTeamId = null;
    public ?int $selectedTournamentId = null;
    public string $opponentName = '';
    public ?int $selectedOpponentId = null;
    public string $matchDate = '';
    public string $matchTime = '';
    public int $halfDuration = 45;
    public int $halvesCount = 2;
    public bool $isAway = false;
    public ?int $selectedVenueId = null;
    public string $description = '';
    public bool $notifyParents = true;

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
        $club = Club::find($this->clubId);

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

        // Load tournaments (по прямой связи club_id или через tournament_teams)
        $this->tournaments = Tournament::where(function ($q) {
                $q->where('club_id', $this->clubId)
                  ->orWhereHas('tournamentTeams', fn($sub) => $sub->where('club_id', $this->clubId));
            })
            ->where('ends_at', '>=', now())
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();

        // Load opponents
        $this->opponents = Opponent::where('club_id', $this->clubId)
            ->orWhereNull('club_id')
            ->get()
            ->map(fn($o) => ['id' => $o->id, 'name' => $o->name])
            ->toArray();

        // Default values
        $this->matchDate = now()->format('Y-m-d');
        $this->matchTime = '18:00';

        if (count($this->teams) === 1) {
            $this->selectedTeamId = $this->teams[0]['id'];
        }
    }

    public function updatedOpponentName($value)
    {
        $this->selectedOpponentId = null;
    }

    public function selectOpponent($id, $name)
    {
        $this->selectedOpponentId = $id;
        $this->opponentName = $name;
    }

    public function save()
    {
        $rules = [
            'selectedTeamId' => 'required|exists:teams,id',
            'matchDate' => 'required|date',
            'matchTime' => 'required',
            'halfDuration' => 'required|integer|min:10|max:60',
            'halvesCount' => 'required|integer|min:1|max:4',
            'selectedVenueId' => 'nullable|exists:venues,id',
        ];

        if ($this->matchType === 'tournament') {
            $rules['selectedTournamentId'] = 'required|exists:tournaments,id';
        }

        if ($this->selectedOpponentId) {
            $rules['selectedOpponentId'] = 'exists:opponents,id';
        } else {
            $rules['opponentName'] = 'required|string|max:255';
        }

        $this->validate($rules, [
            'selectedTeamId.required' => 'Выберите команду',
            'matchDate.required' => 'Укажите дату',
            'matchTime.required' => 'Укажите время',
            'opponentName.required' => 'Укажите соперника',
            'selectedTournamentId.required' => 'Выберите турнир',
        ]);

        // Check team belongs to club
        $team = Team::find($this->selectedTeamId);
        if (!$team || $team->club_id !== $this->clubId) {
            $this->dispatch('notify', type: 'error', message: 'Команда не найдена');
            return;
        }

        // Create or find opponent
        $opponentId = $this->selectedOpponentId;
        if (!$opponentId && $this->opponentName) {
            $opponent = Opponent::create([
                'name' => $this->opponentName,
                'club_id' => $this->clubId,
            ]);
            $opponentId = $opponent->id;
        }

        $club = Club::find($this->clubId);

        $match = GameMatch::create([
            'match_type' => $this->matchType,
            'tournament_id' => $this->matchType === 'tournament' ? $this->selectedTournamentId : null,
            'sport_type_id' => $club?->sport_type_id,
            'club_id' => $this->clubId,
            'team_id' => $this->selectedTeamId,
            'opponent_id' => $opponentId,
            'scheduled_at' => $this->matchDate . ' ' . $this->matchTime,
            'half_duration_minutes' => $this->halfDuration,
            'halves_count' => $this->halvesCount,
            'is_away' => $this->isAway,
            'venue_id' => $this->selectedVenueId,
            'description' => $this->description,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Матч создан');
        return redirect()->route('club.team.show', $this->selectedTeamId);
    }

    public function render()
    {
        return view('livewire.match-create');
    }
}
