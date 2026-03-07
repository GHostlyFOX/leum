<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: role:admin  или  role:admin,super_admin
 *
 * Проверяет, что global_role аутентифицированного пользователя
 * входит в список допустимых ролей.
 *
 * Использование в маршрутах:
 *   Route::middleware('role:admin')
 *   Route::middleware('role:coach,admin')
 */
class CheckGlobalRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Не аутентифицирован.'], 401);
        }

        // super_admin проходит всегда
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $user->hasRole($roles)) {
            return response()->json([
                'message' => 'Недостаточно прав. Требуется роль: ' . implode(', ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
