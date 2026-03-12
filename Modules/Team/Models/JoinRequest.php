<?php

namespace Modules\Team\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;
use Modules\Club\Models\Club;

class JoinRequest extends Model
{
    protected $table = 'join_requests';

    protected $fillable = [
        'user_id',
        'club_id',
        'team_id',
        'type',
        'status',
        'message',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approve(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $adminId,
            'processed_at' => now(),
            'admin_notes' => $notes,
        ]);

        // Создаём привязку пользователя
        if ($this->type === 'club') {
            // Для тренеров - привязка к клубу
            TeamMember::firstOrCreate([
                'user_id' => $this->user_id,
                'club_id' => $this->club_id,
            ], [
                'team_id' => Team::where('club_id', $this->club_id)->first()?->id,
                'role_id' => 8, // coach
                'joined_at' => now(),
                'is_active' => true,
            ]);
        } else {
            // Для игроков/родителей - привязка к команде
            TeamMember::firstOrCreate([
                'user_id' => $this->user_id,
                'team_id' => $this->team_id,
            ], [
                'club_id' => $this->club_id,
                'role_id' => $this->user->global_role === 'parent' ? 5 : 6, // parent или player
                'joined_at' => now(),
                'is_active' => true,
            ]);
        }
    }

    public function reject(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'processed_by' => $adminId,
            'processed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }
}
