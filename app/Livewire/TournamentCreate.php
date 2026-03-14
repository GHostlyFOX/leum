<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Reference\Models\RefTournamentType;
use Modules\Team\Models\TeamMember;
use Modules\Tournament\Models\Tournament;

#[Layout('layouts.app')]
class TournamentCreate extends Component
{
    public ?int $clubId = null;
    public string $name = '';
    public ?int $tournamentTypeId = null;
    public string $startsAt = '';
    public string $endsAt = '';
    public int $halfDurationMinutes = 45;
    public int $halvesCount = 2;
    public string $organizer = '';
    public array $tournamentTypes = [];

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

        // Load tournament types
        $this->tournamentTypes = RefTournamentType::all()
            ->map(fn($t) => ['id' => $t->id, 'name' => $t->name])
            ->toArray();

        // Set default dates
        $this->startsAt = now()->format('Y-m-d');
        $this->endsAt = now()->addDays(7)->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'tournamentTypeId' => 'required|exists:ref_tournament_types,id',
            'startsAt' => 'required|date',
            'endsAt' => 'required|date|after_or_equal:startsAt',
            'halfDurationMinutes' => 'required|integer|min:15|max:120',
            'halvesCount' => 'required|integer|min:1|max:4',
            'organizer' => 'required|string|max:255',
        ], [
            'name.required' => 'Укажите название турнира',
            'tournamentTypeId.required' => 'Выберите тип турнира',
            'startsAt.required' => 'Укажите дату начала',
            'endsAt.required' => 'Укажите дату завершения',
            'endsAt.after_or_equal' => 'Дата завершения должна быть не раньше даты начала',
            'halfDurationMinutes.required' => 'Укажите длительность тайма',
            'halvesCount.required' => 'Укажите количество таймов',
            'organizer.required' => 'Укажите организатора',
        ]);

        $tournament = Tournament::create([
            'club_id' => $this->clubId,
            'name' => $this->name,
            'tournament_type_id' => $this->tournamentTypeId,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'half_duration_minutes' => $this->halfDurationMinutes,
            'halves_count' => $this->halvesCount,
            'organizer' => $this->organizer,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Турнир создан');
        return redirect()->route('tournament.detail', $tournament->id);
    }

    public function render()
    {
        return view('livewire.tournament-create');
    }
}
