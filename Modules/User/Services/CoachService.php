<?php

namespace Modules\User\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\CoachProfile;

class CoachService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = CoachProfile::with(['user', 'sportType', 'specialty']);

        if (! empty($filters['sport_type_id'])) {
            $query->where('sport_type_id', $filters['sport_type_id']);
        }

        if (! empty($filters['club_id'])) {
            $query->whereHas('user.teamMemberships', fn ($q) =>
                $q->where('club_id', $filters['club_id'])->where('is_active', true)
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

    public function find(int $id): CoachProfile
    {
        return CoachProfile::with(['user', 'sportType', 'specialty'])
            ->findOrFail($id);
    }

    public function findOrFail(int $id): CoachProfile
    {
        return CoachProfile::findOrFail($id);
    }

    public function update(CoachProfile $profile, array $data): CoachProfile
    {
        $profile->update($data);
        return $profile->fresh(['user', 'sportType', 'specialty']);
    }

    public function delete(CoachProfile $profile): void
    {
        $profile->delete();
    }
}
