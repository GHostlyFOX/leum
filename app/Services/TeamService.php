<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TeamService
{
    /**
     * Список команд клуба.
     */
    public function listByClub(int $clubId, int $perPage = 15): LengthAwarePaginator
    {
        return Team::where('club_id', $clubId)
            ->with(['sportType', 'country', 'city'])
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Получить команду по ID.
     */
    public function find(int $id): Team
    {
        return Team::with([
            'club', 'sportType', 'country', 'city',
            'members.user', 'members.role',
        ])->findOrFail($id);
    }

    /**
     * Создать команду.
     */
    public function create(array $data): Team
    {
        return Team::create($data);
    }

    /**
     * Обновить команду.
     */
    public function update(Team $team, array $data): Team
    {
        $team->update($data);
        return $team->fresh();
    }

    /**
     * Удалить команду.
     */
    public function delete(Team $team): void
    {
        $team->delete();
    }

    /**
     * Добавить участника в команду.
     */
    public function addMember(int $teamId, array $data): TeamMember
    {
        $data['team_id'] = $teamId;
        return TeamMember::create($data);
    }

    /**
     * Удалить участника из команды (деактивировать).
     */
    public function removeMember(TeamMember $member): void
    {
        $member->update(['is_active' => false]);
    }
}
