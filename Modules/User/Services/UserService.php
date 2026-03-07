<?php

namespace Modules\User\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\CoachProfile;
use Modules\User\Models\PlayerProfile;
use Modules\User\Models\User;
use Modules\User\Models\UserParentPlayer;

class UserService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        return $query->orderBy('last_name')->paginate($perPage);
    }

    public function find(int $id): User
    {
        return User::with([
            'playerProfile.dominantFoot',
            'playerProfile.position',
            'playerProfile.sportType',
            'coachProfile.sportType',
            'coachProfile.specialty',
            'teamMemberships.team',
            'teamMemberships.role',
        ])->findOrFail($id);
    }

    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        $user->update($data);
        return $user->fresh();
    }

    public function createPlayerProfile(int $userId, array $data): PlayerProfile
    {
        $data['user_id'] = $userId;
        return PlayerProfile::create($data);
    }

    public function createCoachProfile(int $userId, array $data): CoachProfile
    {
        $data['user_id'] = $userId;
        return CoachProfile::create($data);
    }

    public function linkParentPlayer(int $parentId, int $playerId, int $kinshipTypeId): UserParentPlayer
    {
        return UserParentPlayer::create([
            'parent_user_id'  => $parentId,
            'player_user_id'  => $playerId,
            'kinship_type_id' => $kinshipTypeId,
        ]);
    }
}
