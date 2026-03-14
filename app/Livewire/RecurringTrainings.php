<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\RecurringTraining;

#[Layout('layouts.app')]
class RecurringTrainings extends Component
{
    public ?int $clubId = null;
    public array $templates = [];

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
        $this->loadTemplates();
    }

    private function loadTemplates()
    {
        $this->templates = RecurringTraining::where('club_id', $this->clubId)
            ->with(['team', 'club'])
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'team_name' => $t->team?->name ?? 'Не указано',
                'schedule' => $t->schedule ?? [],
                'is_active' => $t->is_active,
                'notify_parents' => $t->notify_parents,
                'require_rsvp' => $t->require_rsvp,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.recurring-trainings');
    }
}
