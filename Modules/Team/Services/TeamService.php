<?php

namespace Modules\Team\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

class TeamService
{
    public function listByClub(int $clubId, int $perPage = 15): LengthAwarePaginator
    {
        return Team::where('club_id', $clubId)
            ->with(['sportType', 'country', 'city'])
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function find(int $id): Team
    {
        return Team::with([
            'club', 'sportType', 'country', 'city',
            'members.user', 'members.role',
        ])->findOrFail($id);
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function update(Team $team, array $data): Team
    {
        $team->update($data);
        return $team->fresh();
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }

    public function addMember(int $teamId, array $data): TeamMember
    {
        $data['team_id'] = $teamId;
        return TeamMember::create($data);
    }

    public function removeMember(TeamMember $member): TeamMember
    {
        $member->update(['is_active' => false]);
        return $member->fresh();
    }
}
