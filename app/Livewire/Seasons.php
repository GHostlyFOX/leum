<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Club\Models\Club;
use Modules\Team\Models\Season;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class Seasons extends Component
{
    // List state
    public ?int $clubId = null;
    public string $filterStatus = '';

    // Create / Edit modal
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingSeasonId = null;

    public string $name = '';
    public ?int $sportTypeId = null;
    public string $status = 'planned';
    public string $startDate = '';
    public string $endDate = '';
    public array $selectedTeamIds = [];

    // Delete confirmation
    public bool $showDeleteConfirm = false;
    public ?int $deletingSeasonId = null;
    public ?string $deletingSeasonName = null;

    public function mount()
    {
        $user = Auth::user();
        // Получаем club_id из любого членства пользователя
        $membership = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8]) // 7 - admin, 8 - coach
            ->first();

        $this->clubId = $membership?->club_id;
        
        // Если не нашли по role_id, пробуем найти любое членство
        if (!$this->clubId) {
            $membership = TeamMember::where('user_id', $user->id)->first();
            $this->clubId = $membership?->club_id;
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $seasonId)
    {
        $season = Season::with('teams')->find($seasonId);
        if (!$season || $season->club_id !== $this->clubId) return;

        $this->resetForm();
        $this->isEditing = true;
        $this->editingSeasonId = $seasonId;
        $this->name = $season->name;
        $this->sportTypeId = $season->sport_type_id;
        $this->status = $season->status;
        $this->startDate = $season->start_date?->format('Y-m-d') ?? '';
        $this->endDate = $season->end_date?->format('Y-m-d') ?? '';
        $this->selectedTeamIds = $season->teams->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate([
            'name'       => 'required|string|max:255',
            'status'     => 'required|in:planned,active,archived',
            'startDate'  => 'required|date',
            'endDate'    => 'required|date|after_or_equal:startDate',
        ], [
            'name.required'           => 'Введите название сезона.',
            'startDate.required'      => 'Укажите дату начала.',
            'endDate.required'        => 'Укажите дату окончания.',
            'endDate.after_or_equal'  => 'Дата окончания должна быть позже даты начала.',
        ]);

        if (!$this->clubId) {
            $this->dispatch('notify', type: 'error', message: 'У вас нет доступа к управлению клубом');
            return;
        }

        $club = Club::find($this->clubId);
        $sportTypeId = $this->sportTypeId ?? $club?->sport_type_id;

        $data = [
            'name'          => $this->name,
            'club_id'       => $this->clubId,
            'sport_type_id' => $sportTypeId,
            'status'        => $this->status,
            'start_date'    => $this->startDate,
            'end_date'      => $this->endDate,
        ];

        if ($this->isEditing && $this->editingSeasonId) {
            $season = Season::find($this->editingSeasonId);
            if ($season && $season->club_id === $this->clubId) {
                $season->update($data);
                $season->teams()->sync($this->selectedTeamIds);
            }
        } else {
            $season = Season::create($data);
            if (!empty($this->selectedTeamIds)) {
                $season->teams()->sync($this->selectedTeamIds);
            }
        }

        $this->dispatch('notify', type: 'success', message: $this->isEditing ? 'Сезон обновлён' : 'Сезон создан');
        $this->closeModal();
    }

    public function confirmDelete(int $seasonId)
    {
        $season = Season::find($seasonId);
        if (!$season || $season->club_id !== $this->clubId) return;

        $this->deletingSeasonId = $seasonId;
        $this->deletingSeasonName = $season->name;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->deletingSeasonId = null;
        $this->deletingSeasonName = null;
    }

    public function deleteSeason()
    {
        if (!$this->deletingSeasonId) return;

        $season = Season::find($this->deletingSeasonId);
        if ($season && $season->club_id === $this->clubId) {
            $season->teams()->detach();
            $season->delete();
        }

        $this->cancelDelete();
    }

    private function resetForm()
    {
        $this->editingSeasonId = null;
        $this->name = '';
        $this->sportTypeId = null;
        $this->status = 'planned';
        $this->startDate = '';
        $this->endDate = '';
        $this->selectedTeamIds = [];
    }

    public function render()
    {
        $seasons = collect();
        $teams = collect();

        if ($this->clubId) {
            $query = Season::where('club_id', $this->clubId)
                ->with('teams')
                ->orderBy('start_date', 'desc');

            if ($this->filterStatus) {
                $query->where('status', $this->filterStatus);
            }

            $seasons = $query->get();
            $teams = Team::where('club_id', $this->clubId)->orderBy('name')->get();
        }

        return view('livewire.seasons', [
            'seasons' => $seasons,
            'teams'   => $teams,
        ]);
    }
}
