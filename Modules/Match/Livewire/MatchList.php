<?php

declare(strict_types=1);

namespace Modules\Match\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Match\Models\GameMatch;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class MatchList extends Component
{
    use WithPagination;

    public ?int $clubId = null;
    public ?int $filterTeamId = null;
    public string $filterType = 'all';
    public ?string $filterDateFrom = null;
    public ?string $filterDateTo = null;
    public array $teams = [];

    public function mount(): void
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8])
            ->first();

        if (!$membership) {
            return;
        }

        $this->clubId = $membership->club_id;

        $this->teams = Team::where('club_id', $this->clubId)
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();
    }

    public function updatedFilterTeamId(): void { $this->resetPage(); }
    public function updatedFilterType(): void { $this->resetPage(); }
    public function updatedFilterDateFrom(): void { $this->resetPage(); }
    public function updatedFilterDateTo(): void { $this->resetPage(); }

    public function render(): View
    {
        $query = GameMatch::where('club_id', $this->clubId)
            ->with(['team', 'opponent', 'opponentTeam', 'venue', 'tournament']);

        if ($this->filterTeamId) {
            $query->where('team_id', $this->filterTeamId);
        }
        if ($this->filterType !== 'all') {
            $query->where('match_type', $this->filterType);
        }
        if ($this->filterDateFrom) {
            $query->whereDate('scheduled_at', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $query->whereDate('scheduled_at', '<=', $this->filterDateTo);
        }

        $matches = $query->orderBy('scheduled_at', 'desc')->paginate(10);

        return view('match::livewire.match-list', [
            'matches' => $matches,
        ]);
    }
}
