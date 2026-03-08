<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Models\RefreshToken;
use Modules\User\Models\User;

class AuthService
{
    /**
     * Регистрация нового пользователя.
     * Возвращает: user, access_token, refresh_token, permissions.
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'       => $data['first_name'],
                'last_name'        => $data['last_name'],
                'middle_name'      => $data['middle_name'] ?? null,
                'email'            => $data['email'],
                'phone'            => $data['phone'] ?? null,
                'password_hash'    => Hash::make($data['password']),
                'birth_date'       => $data['birth_date'],
                'gender'           => $data['gender'],
                'global_role'      => $data['role'] ?? 'player',
                'notifications_on' => true,
            ]);

            return $this->issueTokens($user);
        });
    }

    /**
     * Вход по email/phone + пароль.
     */
    public function login(string $login, string $password): array
    {
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user  = User::where($field, $login)->first();

        if (! $user || ! Hash::check($password, $user->password_hash)) {
            throw ValidationException::withMessages([
                'login' => ['Неверные учётные данные.'],
            ]);
        }

        return $this->issueTokens($user);
    }

    /**
     * Обновить пару токенов через refresh-токен.
     */
    public function refresh(string $refreshToken): array
    {
        $record = RefreshToken::where('token', $refreshToken)->first();

        if (! $record || $record->isExpired()) {
            throw ValidationException::withMessages([
                'refresh_token' => ['Refresh-токен недействителен или истёк.'],
            ]);
        }

        $user = $record->user;

        // Удаляем использованный refresh-токен (ротация)
        $record->delete();

        // Удаляем текущий access-токен
        $user->currentAccessToken()?->delete();

        return $this->issueTokens($user);
    }

    /**
     * Выход: удалить текущий access-токен и все refresh-токены.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
        RefreshToken::revokeAll($user->id);
    }

    /**
     * Запрос сброса пароля — генерирует токен и сохраняет в password_resets.
     * В реальном приложении токен отправляется по email.
     */
    public function forgotPassword(string $email): string
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['Пользователь с таким email не найден.'],
            ]);
        }

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // TODO: отправить email с токеном
        // Mail::to($email)->send(new PasswordResetMail($token));

        return $token;
    }

    /**
     * Сбросить пароль по токену.
     */
    public function resetPassword(string $email, string $token, string $newPassword): void
    {
        $record = DB::table('password_resets')->where('email', $email)->first();

        if (! $record || ! Hash::check($token, $record->token)) {
            throw ValidationException::withMessages([
                'token' => ['Неверный или просроченный токен сброса пароля.'],
            ]);
        }

        // Проверяем время жизни токена (60 минут)
        if (now()->diffInMinutes($record->created_at) > 60) {
            throw ValidationException::withMessages([
                'token' => ['Токен сброса пароля истёк.'],
            ]);
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->update(['password_hash' => Hash::make($newPassword)]);

        // Удаляем использованный токен
        DB::table('password_resets')->where('email', $email)->delete();

        // Отзываем все существующие токены
        $user->tokens()->delete();
        RefreshToken::revokeAll($user->id);
    }

    // ────────────────────────────────────────────────────────────

    /**
     * Выпустить пару access + refresh токенов.
     */
    private function issueTokens(User $user): array
    {
        $accessToken  = $user->createToken('api')->plainTextToken;
        $refreshToken = RefreshToken::createForUser($user->id);

        return [
            'user'          => $user,
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken->token,
            'expires_in'    => (int) config('sanctum.expiration', 60) * 60, // секунды
            'permissions'   => $user->getAllPermissions()->values(),
        ];
    }
}
