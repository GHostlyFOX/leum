<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:100',
            'last_name'   => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'email'       => 'required|email|max:255|unique:users,email',
            'phone'       => 'nullable|string|max:30',
            'password'    => 'required|string|min:8|confirmed',
            'birth_date'  => 'required|date',
            'gender'      => 'required|in:male,female',
        ]);

        $user = User::create([
            'first_name'       => $validated['first_name'],
            'last_name'        => $validated['last_name'],
            'middle_name'      => $validated['middle_name'] ?? null,
            'email'            => $validated['email'],
            'phone'            => $validated['phone'] ?? null,
            'password_hash'    => Hash::make($validated['password']),
            'birth_date'       => $validated['birth_date'],
            'gender'           => $validated['gender'],
            'notifications_on' => true,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $login)->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password_hash)) {
            throw ValidationException::withMessages([
                'login' => ['Неверные учётные данные.'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Выход выполнен']);
    }
}
