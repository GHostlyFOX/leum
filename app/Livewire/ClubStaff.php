<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class ClubStaff extends Component
{
    use WithPagination;

    public ?int $clubId = null;
    public string $search = '';
    public string $roleFilter = 'all';

    public function mount()
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8]) // admin или coach
            ->first();

        if (!$membership) {
            $membership = TeamMember::where('user_id', $user->id)->first();
        }

        if (!$membership) {
            return redirect()->route('home')->with('error', 'Вы не привязаны к клубу');
        }

        $this->clubId = $membership->club_id;
    }

    public function render()
    {
        $query = TeamMember::where('club_id', $this->clubId)
            ->with('user')
            ->whereIn('role_id', [7, 8, 11]); // admin, coach, assistant

        if ($this->roleFilter !== 'all') {
            $query->where('role_id', $this->roleFilter);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('first_name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('email', 'ilike', '%' . $this->search . '%');
            });
        }

        $staff = $query->orderBy('role_id')->orderBy('joined_at', 'desc')->paginate(15);

        $stats = [
            'admins' => TeamMember::where('club_id', $this->clubId)->where('role_id', 7)->count(),
            'coaches' => TeamMember::where('club_id', $this->clubId)->where('role_id', 8)->count(),
            'assistants' => TeamMember::where('club_id', $this->clubId)->where('role_id', 11)->count(),
        ];

        return view('livewire.club-staff', compact('staff', 'stats'));
    }
}
