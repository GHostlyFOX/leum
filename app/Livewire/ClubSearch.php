<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Team\Models\JoinRequest;
use Modules\Team\Models\Team;

#[Layout('layouts.app')]
class ClubSearch extends Component
{
    public string $searchQuery = '';
    public ?int $selectedClubId = null;
    public ?int $selectedTeamId = null;
    public string $requestMessage = '';
    public bool $showRequestModal = false;
    public bool $joinAsCoach = false;

    public function mount()
    {
        $user = Auth::user();
        
        // Если пользователь уже привязан к клубу - редирект на дашборд
        if ($user->clubs()->exists() || $user->teams()->exists()) {
            return redirect()->route('home');
        }

        // Определяем тип заявки по роли
        $this->joinAsCoach = in_array($user->global_role, ['coach', 'admin']);
    }

    public function selectClub(int $clubId)
    {
        $this->selectedClubId = $clubId;
        $this->selectedTeamId = null;
    }

    public function openRequestModal()
    {
        if (!$this->selectedClubId) {
            return;
        }

        // Для игроков/родителей обязательно выбрать команду
        if (!$this->joinAsCoach && !$this->selectedTeamId) {
            $this->dispatch('notify', type: 'error', message: 'Выберите команду');
            return;
        }

        $this->showRequestModal = true;
    }

    public function closeRequestModal()
    {
        $this->showRequestModal = false;
        $this->requestMessage = '';
    }

    public function sendRequest()
    {
        if (!$this->selectedClubId) {
            return;
        }

        $user = Auth::user();
        $club = Club::find($this->selectedClubId);

        if (!$club) {
            $this->dispatch('notify', type: 'error', message: 'Клуб не найден');
            return;
        }

        // Проверяем, нет ли уже заявки
        $existingRequest = JoinRequest::where('user_id', $user->id)
            ->where('club_id', $this->selectedClubId)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            $this->dispatch('notify', type: 'warning', message: 'Заявка уже отправлена и ожидает рассмотрения');
            return;
        }

        // Создаём заявку
        $request = JoinRequest::create([
            'user_id' => $user->id,
            'club_id' => $this->selectedClubId,
            'team_id' => $this->selectedTeamId,
            'type' => $this->joinAsCoach ? 'club' : 'team',
            'status' => 'pending',
            'message' => $this->requestMessage ?: null,
        ]);

        $this->closeRequestModal();
        $this->dispatch('notify', type: 'success', message: 'Заявка отправлена! Ожидайте подтверждения от администратора клуба.');
    }

    public function skipOnboarding()
    {
        $user = Auth::user();
        $user->update(['onboarded_at' => now()]);
        return redirect()->route('home');
    }

    public function render()
    {
        $user = Auth::user();
        $clubs = collect();
        $teams = collect();
        $selectedClub = null;

        if ($this->searchQuery && strlen($this->searchQuery) >= 2) {
            $clubs = Club::where('name', 'ilike', '%' . $this->searchQuery . '%')
                ->orWhereHas('city', fn($q) => $q->where('name', 'ilike', '%' . $this->searchQuery . '%'))
                ->with(['city', 'sportType', 'teams'])
                ->limit(20)
                ->get();
        }

        if ($this->selectedClubId) {
            $selectedClub = Club::with(['teams', 'city', 'sportType'])->find($this->selectedClubId);
            $teams = $selectedClub?->teams ?? collect();
        }

        // Проверяем статус заявок пользователя
        $pendingRequests = JoinRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('club')
            ->get();

        return view('livewire.club-search', [
            'clubs' => $clubs,
            'teams' => $teams,
            'selectedClub' => $selectedClub,
            'pendingRequests' => $pendingRequests,
            'joinAsCoach' => $this->joinAsCoach,
        ]);
    }
}
