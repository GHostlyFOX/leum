<?php

namespace App\Services;

use App\Models\CoachProfile;
use App\Models\PlayerProfile;
use App\Models\User;
use App\Models\UserParentPlayer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Список пользователей с фильтрами.
     */
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

        return $query->orderBy('last_name')->orderBy('first_name')->paginate($perPage);
    }

    /**
     * Получить пользователя по ID.
     */
    public function find(int $id): User
    {
        return User::with([
            'playerProfile.dominantFoot',
            'playerProfile.position',
            'playerProfile.sportType',
            'coachProfile.sportType',
            'coachProfile.specialty',
            'teamMemberships.team',
            'teamMemberships.club',
            'teamMemberships.role',
        ])->findOrFail($id);
    }

    /**
     * Создать пользователя.
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);

            return User::create($data);
        });
    }

    /**
     * Обновить пользователя.
     */
    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        $user->update($data);
        return $user->fresh();
    }

    /**
     * Создать профиль игрока.
     */
    public function createPlayerProfile(int $userId, array $data): PlayerProfile
    {
        $data['user_id'] = $userId;
        return PlayerProfile::create($data);
    }

    /**
     * Создать профиль тренера.
     */
    public function createCoachProfile(int $userId, array $data): CoachProfile
    {
        $data['user_id'] = $userId;
        return CoachProfile::create($data);
    }

    /**
     * Привязать родителя к игроку.
     */
    public function linkParentPlayer(int $parentId, int $playerId, int $kinshipTypeId): UserParentPlayer
    {
        return UserParentPlayer::create([
            'parent_user_id'  => $parentId,
            'player_user_id'  => $playerId,
            'kinship_type_id' => $kinshipTypeId,
        ]);
    }
}
