<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Team\Models\JoinRequest;

#[Layout('layouts.app')]
class OnboardingSearch extends Component
{
    public string $searchQuery = '';
    public string $searchType = ''; // 'club' для тренеров, 'team' для родителей/игроков
    public array $searchResults = [];
    public ?int $selectedItemId = null;
    public string $requestMessage = '';
    public bool $showRequestModal = false;
    public ?string $selectedItemName = null;

    public function mount()
    {
        $user = Auth::user();
        
        // Определяем тип поиска по роли
        if ($user->global_role === 'coach') {
            $this->searchType = 'club';
        } elseif (in_array($user->global_role, ['parent', 'player'])) {
            $this->searchType = 'team';
        } else {
            // Если роль не определена, проверяем есть ли уже привязка
            $membership = TeamMember::where('user_id', $user->id)->first();
            if ($membership) {
                return redirect()->route('home');
            }
            // По умолчанию ищем команду
            $this->searchType = 'team';
        }
    }

    public function search()
    {
        if (empty($this->searchQuery) || strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        if ($this->searchType === 'club') {
            $this->searchResults = Club::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                ->with(['city', 'sportType'])
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->searchResults = Team::where('name', 'like', '%' . $this->searchQuery . '%')
                ->with(['club', 'sportType'])
                ->limit(10)
                ->get()
                ->toArray();
        }
    }

    public function selectItem(int $id, string $name)
    {
        $this->selectedItemId = $id;
        $this->selectedItemName = $name;
        $this->showRequestModal = true;
    }

    public function closeModal()
    {
        $this->showRequestModal = false;
        $this->selectedItemId = null;
        $this->selectedItemName = null;
        $this->requestMessage = '';
    }

    public function sendRequest()
    {
        if (!$this->selectedItemId) {
            return;
        }

        $user = Auth::user();

        // Проверяем, нет ли уже активной заявки
        $existingRequest = JoinRequest::where('user_id', $user->id)
            ->where('type', $this->searchType)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            $this->dispatch('notify', type: 'warning', message: 'У вас уже есть активная заявка на вступление');
            return;
        }

        // Создаём заявку
        $requestData = [
            'user_id' => $user->id,
            'type' => $this->searchType,
            'message' => $this->requestMessage,
            'status' => 'pending',
        ];

        if ($this->searchType === 'club') {
            $requestData['club_id'] = $this->selectedItemId;
        } else {
            $requestData['team_id'] = $this->selectedItemId;
            // Получаем club_id из команды
            $team = Team::find($this->selectedItemId);
            if ($team) {
                $requestData['club_id'] = $team->club_id;
            }
        }

        JoinRequest::create($requestData);

        $this->dispatch('notify', type: 'success', message: 'Заявка на вступление отправлена!');
        $this->closeModal();
        
        // Очищаем результаты поиска
        $this->searchResults = [];
        $this->searchQuery = '';
    }

    public function render()
    {
        $user = Auth::user();
        
        // Проверяем статус заявок пользователя
        $pendingRequest = JoinRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['club', 'team'])
            ->first();

        return view('livewire.onboarding-search', [
            'user' => $user,
            'pendingRequest' => $pendingRequest,
            'title' => $this->searchType === 'club' ? 'Найдите свой клуб' : 'Найдите свою команду',
            'subtitle' => $this->searchType === 'club' 
                ? 'Введите название клуба или города' 
                : 'Введите название команды или клуба',
        ]);
    }
}
