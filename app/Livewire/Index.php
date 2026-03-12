<?php

namespace App\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class Index extends Component
{
    public bool $showOnboarding = true;

    // Invite modal
    public bool $showInviteModal = false;
    public ?int $inviteTeamId = null;

    public function mount()
    {
        // Скрыть онбординг если пользователь закрыл его (сохраняем в сессии)
        $this->showOnboarding = !session('hide_onboarding', false);
    }

    public function dismissOnboarding()
    {
        $this->showOnboarding = false;
        session(['hide_onboarding' => true]);
    }

    public function openInviteModal(?int $teamId = null)
    {
        $this->inviteTeamId = $teamId;
        $this->showInviteModal = true;
    }

    #[On('close-invite-modal')]
    public function closeInviteModal()
    {
        $this->showInviteModal = false;
        $this->inviteTeamId = null;
    }

    public function render()
    {
        $user = Auth::user();
        $role = $user->global_role ?? 'player';

        $data = [
            'user' => $user,
            'role' => $role,
            'greeting' => $this->getGreeting(),
        ];

        if ($role === 'admin') {
            $data = array_merge($data, $this->getAdminDashboardData($user));
        }

        return view('livewire.index', $data);
    }

    private function getGreeting(): string
    {
        $hour = (int) now()->format('H');

        if ($hour >= 5 && $hour < 12) {
            return 'Доброе утро';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'Добрый день';
        } elseif ($hour >= 17 && $hour < 22) {
            return 'Добрый вечер';
        }

        return 'Доброй ночи';
    }

    private function getAdminDashboardData($user): array
    {
        // Найти клуб администратора через team_members
        $membership = TeamMember::where('user_id', $user->id)
            ->where('role_id', 7) // Администратор клуба
            ->first();

        $clubId = $membership?->club_id;
        $club = $clubId ? Club::find($clubId) : null;

        // Команды клуба
        $teams = $club ? Team::where('club_id', $club->id)->get() : collect();

        // Участники (кроме самого админа)
        $membersCount = $clubId
            ? TeamMember::where('club_id', $clubId)->where('user_id', '!=', $user->id)->count()
            : 0;

        // Онбординг-чеклист
        $hasTeams = $teams->isNotEmpty();
        $hasSeason = false; // TODO: проверить сезоны когда модуль будет готов
        $hasMembers = $membersCount > 0;
        $hasEvents = false; // TODO: проверить тренировки/матчи

        $completedSteps = collect([$hasSeason, $hasTeams, $hasMembers, $hasEvents])
            ->filter()->count();

        return [
            'club' => $club,
            'teams' => $teams,
            'hasSeason' => $hasSeason,
            'hasTeams' => $hasTeams,
            'hasMembers' => $hasMembers,
            'hasEvents' => $hasEvents,
            'completedSteps' => $completedSteps,
            'totalSteps' => 4,
            'upcomingMatches' => collect(), // TODO
            'weekTrainings' => collect(),   // TODO
            'announcements' => collect(),   // TODO
        ];
    }
}
