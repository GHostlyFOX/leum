<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Team\Models\InviteLink;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.custom-app')]
class JoinTeam extends Component
{
    public string $token = '';

    public ?string $errorMessage = null;
    public ?string $successMessage = null;
    public bool $alreadyMember = false;
    public bool $joined = false;

    // Invite data
    public ?string $teamName = null;
    public ?string $clubName = null;
    public ?string $roleName = null;
    public ?string $roleKey = null;
    public ?int $teamId = null;

    public function mount(string $token)
    {
        $this->token = $token;

        $invite = InviteLink::where('token', $token)->with('team.club')->first();

        if (!$invite) {
            $this->errorMessage = 'Ссылка недействительна или не найдена.';
            return;
        }

        if ($invite->isExpired()) {
            $this->errorMessage = 'Срок действия ссылки истёк.';
            return;
        }

        if ($invite->isLimitReached()) {
            $this->errorMessage = 'Ссылка достигла максимального числа использований.';
            return;
        }

        $this->teamName = $invite->team->name ?? 'Неизвестная команда';
        $this->clubName = $invite->team->club->name ?? '';
        $this->roleKey = $invite->role;
        $this->teamId = $invite->team_id;
        $this->roleName = match ($invite->role) {
            'coach'  => 'Тренер',
            'parent' => 'Родитель',
            default  => 'Игрок',
        };

        // Check if already a member
        if (Auth::check()) {
            $exists = TeamMember::where('user_id', Auth::id())
                ->where('team_id', $invite->team_id)
                ->exists();

            if ($exists) {
                $this->alreadyMember = true;
            }
        }
    }

    public function acceptInvite()
    {
        if (!Auth::check()) {
            // Store token in session and redirect to register
            session(['invite_token' => $this->token]);
            return redirect()->route('auth.register', ['invite' => $this->token]);
        }

        $invite = InviteLink::where('token', $this->token)->with('team')->first();

        if (!$invite || !$invite->isValid()) {
            $this->errorMessage = 'Ссылка больше не действительна.';
            return;
        }

        $user = Auth::user();

        // Double-check membership
        $exists = TeamMember::where('user_id', $user->id)
            ->where('team_id', $invite->team_id)
            ->exists();

        if ($exists) {
            $this->alreadyMember = true;
            return;
        }

        // Map role to role_id
        $roleId = match ($invite->role) {
            'coach'  => 2, // Тренер
            'parent' => 9, // Родитель
            default  => 6, // Игрок
        };

        TeamMember::create([
            'user_id'   => $user->id,
            'club_id'   => $invite->team->club_id,
            'team_id'   => $invite->team_id,
            'role_id'   => $roleId,
            'joined_at' => now(),
            'is_active' => true,
        ]);

        $invite->incrementUsage();

        $this->joined = true;
        $this->successMessage = 'Вы успешно вступили в команду!';
    }

    public function goToDashboard()
    {
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.join-team');
    }
}
