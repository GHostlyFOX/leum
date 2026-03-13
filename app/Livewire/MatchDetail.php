<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Match\Models\GameMatch;

#[Layout('layouts.app')]
class MatchDetail extends Component
{
    public ?int $matchId = null;
    public ?GameMatch $match = null;
    public array $events = [];
    public array $lineup = [];
    public bool $canEdit = false;

    public function mount(int $id)
    {
        $this->matchId = $id;
        $this->loadMatch();
    }

    public function loadMatch()
    {
        $this->match = GameMatch::with([
            'team.club',
            'opponent',
            'tournament',
            'venue',
            'events.player',
            'lineup.player',
        ])->findOrFail($this->matchId);

        // Проверка прав
        $user = Auth::user();
        $this->canEdit = $user->hasPermission('matches.manage');

        // События матча
        $this->events = $this->match->events
            ->sortBy('minute')
            ->map(fn($e) => [
                'id' => $e->id,
                'minute' => $e->minute,
                'type' => $e->event_type,
                'player' => $e->player?->full_name,
                'description' => $e->description,
            ])
            ->toArray();

        // Состав
        $this->lineup = $this->match->lineup
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->player?->full_name,
                'is_starting' => $p->is_starting,
                'position' => $p->position,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.match-detail');
    }
}
