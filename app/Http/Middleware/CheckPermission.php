<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: permission:clubs.create  или  permission:clubs.create,clubs.update
 *
 * Проверяет, что у пользователя есть хотя бы одно из указанных разрешений
 * на основе его global_role и таблицы role_permissions.
 *
 * super_admin проходит автоматически.
 *
 * Использование:
 *   Route::post('clubs', ...)->middleware('permission:clubs.create');
 *   Route::put('clubs/{id}', ...)->middleware('permission:clubs.update');
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Не аутентифицирован.'], 401);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($permissions as $perm) {
            if ($user->hasPermission($perm)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Недостаточно прав. Требуется разрешение: ' . implode(' или ', $permissions),
        ], 403);
    }
}
