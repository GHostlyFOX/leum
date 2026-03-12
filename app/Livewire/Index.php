<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    // Season creation modal (from onboarding)
    public bool $showSeasonModal = false;
    public string $seasonName = '';
    public string $seasonStartDate = '';
    public string $seasonEndDate = '';
    public string $seasonStatus = 'planned';

    public function mount()
    {
        $this->showOnboarding = !session('hide_onboarding', false);
    }

    public function dismissOnboarding()
    {
        $this->showOnboarding = false;
        session(['hide_onboarding' => true]);
    }

    // ── Invite Modal ──────────────────────────────────────────────

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

    // ── Season Modal ──────────────────────────────────────────────

    public function openSeasonModal()
    {
        $this->resetSeasonForm();
        $this->showSeasonModal = true;
    }

    public function closeSeasonModal()
    {
        $this->showSeasonModal = false;
        $this->resetSeasonForm();
    }

    public function createSeason()
    {
        $this->validate([
            'seasonName'      => 'required|string|max:255',
            'seasonStartDate' => 'required|date',
            'seasonEndDate'   => 'required|date|after_or_equal:seasonStartDate',
        ], [
            'seasonName.required'          => 'Введите название сезона.',
            'seasonStartDate.required'     => 'Укажите дату начала.',
            'seasonEndDate.required'       => 'Укажите дату окончания.',
            'seasonEndDate.after_or_equal' => 'Дата окончания должна быть позже даты начала.',
        ]);

        $user = Auth::user();
        $membership = TeamMember::where('user_id', $user->id)
            ->where('role_id', 7)
            ->first();

        if (!$membership?->club_id) return;

        $club = Club::find($membership->club_id);

        DB::table('seasons')->insert([
            'name'          => $this->seasonName,
            'club_id'       => $membership->club_id,
            'sport_type_id' => $club?->sport_type_id,
            'status'        => $this->seasonStatus,
            'start_date'    => $this->seasonStartDate,
            'end_date'      => $this->seasonEndDate,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->closeSeasonModal();
    }

    private function resetSeasonForm()
    {
        $this->seasonName = '';
        $this->seasonStartDate = '';
        $this->seasonEndDate = '';
        $this->seasonStatus = 'planned';
    }

    // ── Render ─────────────────────────────────────────────────────

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
        $membership = TeamMember::where('user_id', $user->id)
            ->where('role_id', 7)
            ->first();

        $clubId = $membership?->club_id;
        $club = $clubId ? Club::find($clubId) : null;

        $teams = $club ? Team::where('club_id', $club->id)->get() : collect();

        $membersCount = $clubId
            ? TeamMember::where('club_id', $clubId)->where('user_id', '!=', $user->id)->count()
            : 0;

        // Онбординг-чеклист
        $hasTeams = $teams->isNotEmpty();
        $hasSeason = $clubId ? DB::table('seasons')->where('club_id', $clubId)->exists() : false;
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
            'upcomingMatches' => collect(),
            'weekTrainings' => collect(),
            'announcements' => collect(),
        ];
    }
}
