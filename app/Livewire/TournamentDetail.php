<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\TeamMember;
use Modules\Tournament\Models\Tournament;

#[Layout('layouts.app')]
class TournamentDetail extends Component
{
    public ?int $tournamentId = null;
    public ?Tournament $tournament = null;
    public array $teams = [];
    public array $matches = [];
    public bool $canEdit = false;

    public function mount(int $id)
    {
        $this->tournamentId = $id;
        $this->loadTournament();
    }

    public function loadTournament()
    {
        $this->tournament = Tournament::with([
            'club',
            'tournamentType',
            'tournamentTeams.club',
            'tournamentTeams.team',
            'matches.team',
            'matches.opponent',
        ])->findOrFail($this->tournamentId);

        // Проверка прав через TeamMember
        $user = Auth::user();
        $this->canEdit = $this->tournament->club_id
            ? TeamMember::where('user_id', $user->id)
                ->where('club_id', $this->tournament->club_id)
                ->whereIn('role_id', [7, 8]) // admin or coach
                ->exists()
            : false;

        // Команды турнира
        $this->teams = $this->tournament->tournamentTeams
            ->map(fn($tt) => [
                'id' => $tt->id,
                'teamName' => $tt->team?->name ?? 'Не указано',
                'clubName' => $tt->club?->name ?? 'Не указано',
                'status' => $tt->status,
            ])
            ->toArray();

        // Матчи турнира
        $this->matches = $this->tournament->matches
            ->sortBy('created_at')
            ->map(fn($m) => [
                'id' => $m->id,
                'homeTeam' => $m->team?->name ?? 'Не указано',
                'awayTeam' => $m->opponent?->name ?? 'Не указано',
                'scheduledAt' => $m->scheduled_at?->format('d.m.Y H:i'),
                'status' => $m->actual_end_at ? 'finished' : ($m->actual_start_at ? 'live' : 'scheduled'),
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.tournament-detail');
    }
}
