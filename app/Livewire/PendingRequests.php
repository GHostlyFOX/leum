<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Team\Models\JoinRequest;
use Modules\Team\Models\TeamMember;

class PendingRequests extends Component
{
    public ?int $clubId = null;
    public ?int $teamId = null;
    public string $viewMode = 'dashboard'; // dashboard, club, team

    public function mount(?int $clubId = null, ?int $teamId = null, string $viewMode = 'dashboard')
    {
        $this->clubId = $clubId;
        $this->teamId = $teamId;
        $this->viewMode = $viewMode;
    }

    public function approve(int $requestId)
    {
        $request = JoinRequest::find($requestId);
        if (!$request || $request->status !== 'pending') {
            return;
        }

        // Проверяем права
        if (!$this->canManageRequest($request)) {
            $this->dispatch('notify', type: 'error', message: 'Нет доступа к этой заявке');
            return;
        }

        $request->approve(Auth::id());
        $this->dispatch('notify', type: 'success', message: 'Заявка одобрена');
    }

    public function reject(int $requestId)
    {
        $request = JoinRequest::find($requestId);
        if (!$request || $request->status !== 'pending') {
            return;
        }

        // Проверяем права
        if (!$this->canManageRequest($request)) {
            $this->dispatch('notify', type: 'error', message: 'Нет доступа к этой заявке');
            return;
        }

        $request->reject(Auth::id());
        $this->dispatch('notify', type: 'info', message: 'Заявка отклонена');
    }

    private function canManageRequest(JoinRequest $request): bool
    {
        $user = Auth::user();
        
        // Супер-админ может управлять всеми заявками
        if ($user->global_role === 'admin') {
            return true;
        }

        // Получаем членство пользователя
        $membership = TeamMember::where('user_id', $user->id)
            ->where('club_id', $request->club_id)
            ->first();

        if (!$membership) {
            return false;
        }

        // Заявки в клуб (тренеры) - только админы клуба
        if ($request->type === 'club') {
            return $membership->role_id === 7; // admin
        }

        // Заявки в команду (игроки/родители) - админы и тренера
        if ($request->type === 'team') {
            // Админ клуба может управлять всеми заявками в команды клуба
            if ($membership->role_id === 7) {
                return true;
            }
            // Тренер может управлять заявками только своей команды
            if ($membership->role_id === 8) {
                return $request->team_id === $membership->team_id;
            }
        }

        return false;
    }

    public function render()
    {
        $user = Auth::user();
        $requests = collect();

        // Получаем клубы/команды, где пользователь админ или тренер
        $adminMemberships = TeamMember::where('user_id', $user->id)
            ->where('role_id', 7) // admin
            ->get();

        $coachMemberships = TeamMember::where('user_id', $user->id)
            ->where('role_id', 8) // coach
            ->get();

        $adminClubIds = $adminMemberships->pluck('club_id')->toArray();
        $coachTeamIds = $coachMemberships->pluck('team_id')->toArray();
        $coachClubIds = $coachMemberships->pluck('club_id')->toArray();

        $query = JoinRequest::with(['user', 'club', 'team'])
            ->where('status', 'pending');

        if ($this->viewMode === 'dashboard') {
            // На дашборде показываем все заявки, доступные пользователю
            $query->where(function ($q) use ($adminClubIds, $coachClubIds, $coachTeamIds) {
                // Заявки в клуб (тренеры) - для админов
                if (!empty($adminClubIds)) {
                    $q->where(function ($sq) use ($adminClubIds) {
                        $sq->where('type', 'club')
                           ->whereIn('club_id', $adminClubIds);
                    });
                }

                // Заявки в команду (игроки/родители) - для админов и тренеров
                $clubIdsForTeamRequests = array_unique(array_merge($adminClubIds, $coachClubIds));
                if (!empty($clubIdsForTeamRequests)) {
                    $q->orWhere(function ($sq) use ($clubIdsForTeamRequests, $coachTeamIds, $adminClubIds) {
                        $sq->where('type', 'team');
                        
                        // Админы видят все заявки в команды своего клуба
                        if (!empty($adminClubIds)) {
                            $sq->whereIn('club_id', $adminClubIds);
                        } else {
                            // Тренера видят только заявки в свои команды
                            $sq->whereIn('team_id', $coachTeamIds);
                        }
                    });
                }
            });
        } elseif ($this->viewMode === 'club' && $this->clubId) {
            // На странице клуба показываем заявки в этот клуб
            if (in_array($this->clubId, $adminClubIds)) {
                // Админ видит заявки в клуб (тренеров)
                $query->where('club_id', $this->clubId)
                      ->where('type', 'club');
            } else {
                $requests = collect(); // Нет доступа
                return view('livewire.pending-requests', compact('requests'));
            }
        } elseif ($this->viewMode === 'team' && $this->teamId) {
            // На странице команды показываем заявки в эту команду
            $canManage = in_array($this->teamId, $coachTeamIds) || 
                        in_array($this->clubId ?? Team::find($this->teamId)?->club_id, $adminClubIds);
            
            if ($canManage) {
                $query->where('team_id', $this->teamId)
                      ->where('type', 'team');
            } else {
                $requests = collect(); // Нет доступа
                return view('livewire.pending-requests', compact('requests'));
            }
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return view('livewire.pending-requests', compact('requests'));
    }
}
