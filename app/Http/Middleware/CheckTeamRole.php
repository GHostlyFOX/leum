<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: team.role:coach  или  team.role:coach,team_manager
 *
 * Проверяет, что пользователь имеет нужную роль в конкретной команде.
 *
 * Team ID берётся из параметров маршрута в следующем порядке приоритета:
 *   1. {teamId}
 *   2. {team_id}
 *   3. {id} (если маршрут содержит "teams")
 *
 * Использование:
 *   Route::put('teams/{teamId}', ...)->middleware('team.role:coach,team_manager');
 */
class CheckTeamRole
{
    public function handle(Request $request, Closure $next, string ...$roleNames): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Не аутентифицирован.'], 401);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $teamId = $this->resolveTeamId($request);

        if (! $teamId) {
            return response()->json([
                'message' => 'Невозможно определить команду для проверки роли.',
            ], 400);
        }

        if (! $user->hasTeamRole((int) $teamId, $roleNames)) {
            return response()->json([
                'message' => 'Недостаточно прав в этой команде. Требуется роль: ' . implode(', ', $roleNames),
            ], 403);
        }

        return $next($request);
    }

    private function resolveTeamId(Request $request): mixed
    {
        return $request->route('teamId')
            ?? $request->route('team_id')
            ?? ($request->route('id') && str_contains($request->path(), 'team') ? $request->route('id') : null);
    }
}
