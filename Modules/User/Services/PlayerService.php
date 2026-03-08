<?php

namespace Modules\User\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\PlayerProfile;

class PlayerService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = PlayerProfile::with(['user', 'dominantFoot', 'position', 'sportType']);

        if (! empty($filters['sport_type_id'])) {
            $query->where('sport_type_id', $filters['sport_type_id']);
        }

        if (! empty($filters['club_id'])) {
            $query->whereHas('user.teamMemberships', fn ($q) =>
                $q->where('club_id', $filters['club_id'])->where('is_active', true)
            );
        }

        if (! empty($filters['team_id'])) {
            $query->whereHas('user.teamMemberships', fn ($q) =>
                $q->where('team_id', $filters['team_id'])->where('is_active', true)
            );
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', fn ($q) =>
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
            );
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): PlayerProfile
    {
        return PlayerProfile::with(['user', 'dominantFoot', 'position', 'sportType'])
            ->findOrFail($id);
    }

    public function findOrFail(int $id): PlayerProfile
    {
        return PlayerProfile::findOrFail($id);
    }

    public function update(PlayerProfile $profile, array $data): PlayerProfile
    {
        $profile->update($data);
        return $profile->fresh(['user', 'dominantFoot', 'position', 'sportType']);
    }

    public function delete(PlayerProfile $profile): void
    {
        $profile->delete();
    }
}
