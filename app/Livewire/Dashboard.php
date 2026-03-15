<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Team\Models\Season;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public bool $showOnboarding = true;

    // Invite modal
    public bool $showInviteModal = false;
    public ?int $inviteTeamId = null;

    // Create Event modal
    public bool $showCreateEventModal = false;
    public string $selectedEventType = '';

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
    public ?string $inviteRole = null;

    public function openInviteModal(?int $teamId = null, ?string $role = null)
    {
        $this->inviteTeamId = $teamId;
        $this->inviteRole = $role;
        $this->showInviteModal = true;
    }

    #[On('close-invite-modal')]
    public function closeInviteModal()
    {
        $this->showInviteModal = false;
        $this->inviteTeamId = null;
    }

    // ── Create Event Modal ────────────────────────────────────────

    public function openCreateEventModal()
    {
        $this->showCreateEventModal = true;
        $this->selectedEventType = '';
    }

    public function closeCreateEventModal()
    {
        $this->showCreateEventModal = false;
        $this->selectedEventType = '';
    }

    public function selectEventType(string $type)
    {
        $this->selectedEventType = $type;
    }

    public function createEvent()
    {
        if (empty($this->selectedEventType)) {
            return;
        }

        switch ($this->selectedEventType) {
            case 'announcement':
                return redirect()->route('announcement.create');
            case 'training':
                return redirect()->route('training.create');
            case 'match':
                return redirect()->route('match.create');
        }
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

        if (!$club?->sport_type_id) {
            $this->addError('seasonName', 'У клуба не указан вид спорта. Сначала настройте клуб.');
            return;
        }

        try {
            Season::create([
                'name'          => $this->seasonName,
                'club_id'       => $membership->club_id,
                'sport_type_id' => $club->sport_type_id,
                'status'        => $this->seasonStatus,
                'start_date'    => $this->seasonStartDate,
                'end_date'      => $this->seasonEndDate,
            ]);

            $this->closeSeasonModal();
        } catch (\Exception $e) {
            $this->addError('seasonName', 'Ошибка при создании сезона: ' . $e->getMessage());
        }
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
        } elseif ($role === 'coach') {
            $data = array_merge($data, $this->getCoachDashboardData($user));
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

    private function getCoachDashboardData($user): array
    {
        // Получаем членства тренера (может быть в нескольких командах)
        $memberships = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [8, 11]) // coach, assistant
            ->with('team.club')
            ->get();

        $teamIds = $memberships->pluck('team_id')->toArray();
        $clubIds = $memberships->pluck('club_id')->unique()->toArray();

        // Команды тренера с количеством игроков
        $teams = Team::whereIn('id', $teamIds)
            ->withCount(['members' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        // Тренировки на текущую неделю
        $weekTrainings = collect();
        try {
            $weekTrainings = \Modules\Training\Models\Training::whereIn('team_id', $teamIds)
                ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                ->with('venue', 'team')
                ->orderBy('start_time')
                ->get();
        } catch (\Exception $e) {
            // Модель может не существовать
        }

        // Матчи и соревнования на текущую неделю
        $weekMatches = collect();
        $upcomingTournaments = collect();
        try {
            $weekMatches = \Modules\Match\Models\GameMatch::whereIn('team_id', $teamIds)
                ->whereBetween('match_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->with('team')
                ->orderBy('match_date')
                ->get();

            $upcomingTournaments = \Modules\Tournament\Models\Tournament::whereIn('club_id', $clubIds)
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // Модель может не существовать
        }

        // Заявки на вступление в команды тренера
        $pendingRequests = collect();
        try {
            $pendingRequests = \Modules\Team\Models\JoinRequest::whereIn('team_id', $teamIds)
                ->where('status', 'pending')
                ->where('type', 'team')
                ->with(['user', 'team'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Модель может не существовать
        }

        // Объявления от клуба или для команд тренера
        $announcements = collect();
        try {
            $announcements = \Modules\Training\Models\Announcement::where(function ($q) use ($clubIds, $teamIds) {
                $q->whereIn('club_id', $clubIds)
                  ->orWhereIn('team_id', $teamIds);
            })
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // Модель может не существовать
        }

        return [
            'teams' => $teams,
            'weekTrainings' => $weekTrainings,
            'weekMatches' => $weekMatches,
            'upcomingTournaments' => $upcomingTournaments,
            'pendingRequests' => $pendingRequests,
            'announcements' => $announcements,
            'totalPlayers' => $teams->sum('members_count'),
        ];
    }

    public function approveRequest(int $requestId)
    {
        $user = Auth::user();
        
        try {
            $request = \Modules\Team\Models\JoinRequest::find($requestId);
            if (!$request || $request->status !== 'pending') {
                $this->dispatch('notify', type: 'error', message: 'Заявка не найдена');
                return;
            }

            // Проверяем, что тренер имеет доступ к этой команде
            $hasAccess = TeamMember::where('user_id', $user->id)
                ->where('team_id', $request->team_id)
                ->whereIn('role_id', [8, 11]) // coach или assistant
                ->exists();

            if (!$hasAccess && $user->global_role !== 'admin') {
                $this->dispatch('notify', type: 'error', message: 'Нет доступа');
                return;
            }

            $request->approve($user->id);
            $this->dispatch('notify', type: 'success', message: 'Заявка одобрена');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Ошибка: ' . $e->getMessage());
        }
    }
}
