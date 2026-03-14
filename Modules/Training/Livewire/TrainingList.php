<?php

declare(strict_types=1);

namespace Modules\Training\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Training;

#[Layout('layouts.app')]
class TrainingList extends Component
{
    use WithPagination;

    public ?int $clubId = null;
    public array $teams = [];
    public ?int $filterTeamId = null;
    public ?string $filterStatus = null;
    public ?string $filterDateFrom = null;
    public ?string $filterDateTo = null;

    public function mount()
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8])
            ->first();

        if (!$membership) {
            return redirect()->route('home')->with('error', 'Нет доступа');
        }

        $this->clubId = $membership->club_id;

        $this->teams = Team::where('club_id', $this->clubId)
            ->get()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();
    }

    public function updatingFilterTeamId() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterDateFrom() { $this->resetPage(); }
    public function updatingFilterDateTo() { $this->resetPage(); }

    public function render()
    {
        $query = Training::where('club_id', $this->clubId)
            ->with(['team', 'venue', 'coach']);

        if ($this->filterTeamId) {
            $query->where('team_id', $this->filterTeamId);
        }
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }
        if ($this->filterDateFrom) {
            $query->whereDate('training_date', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $query->whereDate('training_date', '<=', $this->filterDateTo);
        }

        $trainings = $query->orderBy('training_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('training::livewire.training-list', [
            'trainings' => $trainings,
        ]);
    }
}
