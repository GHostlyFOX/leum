<?php

declare(strict_types=1);

namespace Modules\Training\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\TeamMember;
use Modules\Training\Models\Venue;

#[Layout('layouts.app')]
class VenueList extends Component
{
    public ?int $clubId = null;
    public array $venues = [];
    public bool $showCreateForm = false;
    public string $newVenueName = '';
    public string $newVenueAddress = '';

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
        $this->loadVenues();
    }

    private function loadVenues()
    {
        $this->venues = Venue::where('club_id', $this->clubId)
            ->get()
            ->map(fn($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'address' => $v->address,
                'city' => $v->city,
            ])
            ->toArray();
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->resetCreateForm();
        }
    }

    public function createVenue()
    {
        $this->validate([
            'newVenueName' => 'required|string|max:255',
            'newVenueAddress' => 'nullable|string|max:255',
        ], [
            'newVenueName.required' => 'Укажите название площадки',
            'newVenueName.max' => 'Название не должно превышать 255 символов',
        ]);

        Venue::create([
            'club_id' => $this->clubId,
            'name' => $this->newVenueName,
            'address' => $this->newVenueAddress,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Площадка добавлена');
        $this->resetCreateForm();
        $this->loadVenues();
    }

    public function deleteVenue(int $id)
    {
        $venue = Venue::find($id);
        if ($venue && $venue->club_id === $this->clubId) {
            $venue->delete();
            $this->dispatch('notify', type: 'success', message: 'Площадка удалена');
            $this->loadVenues();
        }
    }

    private function resetCreateForm()
    {
        $this->newVenueName = '';
        $this->newVenueAddress = '';
        $this->showCreateForm = false;
    }

    public function render()
    {
        return view('training::livewire.venue-list');
    }
}
