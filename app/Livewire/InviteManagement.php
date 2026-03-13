<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Team\Models\InviteLink;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class InviteManagement extends Component
{
    use WithPagination;

    public ?int $clubId = null;
    public string $statusFilter = 'all';
    public string $typeFilter = 'all';
    public ?int $revokeInviteId = null;
    public bool $showRevokeConfirm = false;

    public function mount()
    {
        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8])
            ->first();

        if (!$membership) {
            $membership = TeamMember::where('user_id', $user->id)->first();
        }

        if (!$membership) {
            return redirect()->route('home')->with('error', 'Вы не привязаны к клубу');
        }

        $this->clubId = $membership->club_id;
    }

    public function confirmRevoke(int $inviteId)
    {
        $this->revokeInviteId = $inviteId;
        $this->showRevokeConfirm = true;
    }

    public function cancelRevoke()
    {
        $this->showRevokeConfirm = false;
        $this->revokeInviteId = null;
    }

    public function revokeInvite()
    {
        if (!$this->revokeInviteId) {
            return;
        }

        $invite = InviteLink::find($this->revokeInviteId);
        if ($invite) {
            $invite->delete();
            $this->dispatch('notify', type: 'success', message: 'Приглашение отозвано');
        }

        $this->cancelRevoke();
    }

    public function render()
    {
        $query = InviteLink::with(['team', 'creator'])
            ->whereHas('team', function ($q) {
                $q->where('club_id', $this->clubId);
            });

        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'active') {
                $query->where('expires_at', '>', now())
                      ->whereColumn('used_count', '<', 'max_uses');
            } elseif ($this->statusFilter === 'expired') {
                $query->where(function ($q) {
                    $q->where('expires_at', '<=', now())
                      ->orWhereColumn('used_count', '>=', 'max_uses');
                });
            }
        }

        if ($this->typeFilter !== 'all') {
            $query->where('role', $this->typeFilter);
        }

        $invites = $query->orderBy('created_at', 'desc')->paginate(15);

        // Статистика
        $stats = [
            'total' => InviteLink::whereHas('team', fn($q) => $q->where('club_id', $this->clubId))->count(),
            'active' => InviteLink::whereHas('team', fn($q) => $q->where('club_id', $this->clubId))
                ->where('expires_at', '>', now())
                ->whereColumn('used_count', '<', 'max_uses')
                ->count(),
            'used' => InviteLink::whereHas('team', fn($q) => $q->where('club_id', $this->clubId))
                ->sum('used_count'),
        ];

        return view('livewire.invite-management', compact('invites', 'stats'));
    }
}
