<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Перенаправляет пользователя на онбординг, если он ещё не прошёл его.
 *
 * Проверяет поле `onboarded_at` в таблице users.
 * Если NULL — пользователь перенаправляется на /onboarding.
 */
class EnsureOnboarded
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && is_null($user->onboarded_at)) {
            // Не редиректим, если пользователь уже на странице онбординга
            if (! $request->routeIs('onboarding')) {
                return redirect()->route('onboarding');
            }
        }

        return $next($request);
    }
}
