<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permission extends Model
{
    protected $table = 'permissions';
    public $timestamps = false;

    protected $fillable = ['slug', 'name', 'group'];

    // ── Helpers ──────────────────────────────────────────────────

    /**
     * Все slug-и разрешений для заданной глобальной роли.
     *
     * @return \Illuminate\Support\Collection<int, string>
     */
    public static function forRole(string $role): \Illuminate\Support\Collection
    {
        return DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role', $role)
            ->pluck('permissions.slug');
    }
}
