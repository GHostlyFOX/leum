<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Team\Models\TeamMember;
use Modules\Tournament\Models\Tournament;

#[Layout('layouts.app')]
class TournamentList extends Component
{
    use WithPagination;

    public ?int $clubId = null;
    public ?int $filterYear = null;
    public array $years = [];

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

        // Load available years from tournaments (PostgreSQL syntax)
        $this->years = Tournament::where(function ($q) {
                $q->where('club_id', $this->clubId)
                  ->orWhereHas('tournamentTeams', fn($sub) => $sub->where('club_id', $this->clubId));
            })
            ->selectRaw('EXTRACT(YEAR FROM starts_at)::int as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        // Set current year as default filter if available
        $currentYear = now()->year;
        if (in_array($currentYear, $this->years)) {
            $this->filterYear = $currentYear;
        } elseif (count($this->years) > 0) {
            $this->filterYear = $this->years[0];
        }
    }

    public function updatingFilterYear()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Tournament::where(function ($q) {
                $q->where('club_id', $this->clubId)
                  ->orWhereHas('tournamentTeams', fn($sub) => $sub->where('club_id', $this->clubId));
            })
            ->with(['tournamentType', 'tournamentTeams', 'matches']);

        if ($this->filterYear) {
            $query->whereRaw('EXTRACT(YEAR FROM starts_at) = ?', [$this->filterYear]);
        }

        $tournaments = $query->orderBy('starts_at', 'desc')
            ->paginate(12);

        return view('livewire.tournament-list', [
            'tournaments' => $tournaments,
        ]);
    }
}
