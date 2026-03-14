<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Modules\Club\Models\Club;
use Modules\File\Services\FileService;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class TeamManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    public ?int $clubId = null;
    
    // Form fields
    public string $teamName = '';
    public ?int $teamBirthYear = null;
    public string $teamGender = 'boys';
    public string $teamColor = '#8fbd56';
    public $teamLogo = null;
    public ?string $existingLogoUrl = null;
    public ?int $editingTeamId = null;
    
    // UI states
    public bool $showForm = false;
    public bool $showDeleteConfirm = false;
    public ?int $deletingTeamId = null;
    
    protected array $rules = [
        'teamName' => 'required|string|max:255',
        'teamBirthYear' => 'required|integer|min:2000|max:2030',
        'teamGender' => 'required|in:boys,girls,mixed',
        'teamColor' => 'required|string|size:7',
    ];

    protected array $messages = [
        'teamName.required' => 'Введите название команды',
        'teamBirthYear.required' => 'Укажите год рождения',
        'teamBirthYear.min' => 'Год должен быть не ранее 2000',
        'teamBirthYear.max' => 'Год должен быть не позднее 2030',
        'teamGender.required' => 'Выберите пол команды',
        'teamGender.in' => 'Выберите пол из списка: Мальчики, Девочки или Смешанная',
    ];

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

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingTeamId = null;
    }

    public function openEditForm(int $teamId)
    {
        $team = Team::find($teamId);
        if (!$team || $team->club_id !== $this->clubId) {
            $this->dispatch('notify', type: 'error', message: 'Команда не найдена');
            return;
        }

        $this->teamName = $team->name;
        $this->teamBirthYear = $team->birth_year;
        $this->teamGender = $team->gender ?? 'boys';
        $this->teamColor = $team->team_color ?? '#8fbd56';
        $this->editingTeamId = $teamId;
        $this->teamLogo = null;
        $this->existingLogoUrl = $team->logoFile?->url ?? null;
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function saveTeam()
    {
        $rules = [
            'teamName' => 'required|string|max:255',
            'teamBirthYear' => 'required|integer|min:2000|max:2030',
            'teamGender' => 'required|in:boys,girls,mixed',
            'teamColor' => 'required|string|size:7',
        ];
        
        if ($this->teamLogo) {
            $rules['teamLogo'] = 'image|max:2048';
        }
        
        $this->validate($rules);

        $club = Club::find($this->clubId);
        
        $teamData = [
            'name' => $this->teamName,
            'birth_year' => $this->teamBirthYear,
            'gender' => $this->teamGender,
            'team_color' => $this->teamColor,
            'club_id' => $this->clubId,
            'sport_type_id' => $club?->sport_type_id,
            'country_id' => $club?->country_id,
            'city_id' => $club?->city_id,
        ];

        // Upload logo if provided
        if ($this->teamLogo) {
            $logoFile = app(FileService::class)->uploadPublic($this->teamLogo, 'teams');
            $teamData['logo_file_id'] = $logoFile->id;
        }

        if ($this->editingTeamId) {
            $team = Team::find($this->editingTeamId);
            if ($team && $team->club_id === $this->clubId) {
                $team->update($teamData);
                $this->dispatch('notify', type: 'success', message: 'Команда обновлена');
            }
        } else {
            Team::create($teamData);
            $this->dispatch('notify', type: 'success', message: 'Команда создана');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $teamId)
    {
        $team = Team::find($teamId);
        if (!$team || $team->club_id !== $this->clubId) {
            $this->dispatch('notify', type: 'error', message: 'Команда не найдена');
            return;
        }

        $this->deletingTeamId = $teamId;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->deletingTeamId = null;
    }

    public function deleteTeam()
    {
        if (!$this->deletingTeamId) {
            return;
        }

        $team = Team::find($this->deletingTeamId);
        if ($team && $team->club_id === $this->clubId) {
            // Проверяем, есть ли члены команды
            $membersCount = TeamMember::where('team_id', $team->id)->count();
            if ($membersCount > 0) {
                $this->dispatch('notify', type: 'warning', message: "Нельзя удалить команду с {$membersCount} участниками. Сначала исключите всех игроков.");
                $this->cancelDelete();
                return;
            }

            $team->delete();
            $this->dispatch('notify', type: 'success', message: 'Команда удалена');
        }

        $this->cancelDelete();
    }

    private function resetForm()
    {
        $this->teamName = '';
        $this->teamBirthYear = null;
        $this->teamGender = 'boys';
        $this->teamColor = '#8fbd56';
        $this->teamLogo = null;
        $this->existingLogoUrl = null;
        $this->resetValidation();
    }

    public function render()
    {
        $teams = Team::where('club_id', $this->clubId)
            ->withCount(['members' => function ($query) {
                $query->where('is_active', true)
                      ->where('role_id', 10); // только игроки
            }])
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.team-management', [
            'teams' => $teams,
        ]);
    }
}
