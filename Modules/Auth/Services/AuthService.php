<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Models\User;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'first_name'       => $data['first_name'],
            'last_name'        => $data['last_name'],
            'middle_name'      => $data['middle_name'] ?? null,
            'email'            => $data['email'],
            'phone'            => $data['phone'] ?? null,
            'password_hash'    => Hash::make($data['password']),
            'birth_date'       => $data['birth_date'],
            'gender'           => $data['gender'],
            'notifications_on' => true,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function login(string $login, string $password): array
    {
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user  = User::where($field, $login)->first();

        if (! $user || ! Hash::check($password, $user->password_hash)) {
            throw ValidationException::withMessages([
                'login' => ['Неверные учётные данные.'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}
