<?php

namespace Modules\Auth\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

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

        $result = $this->authService->register($validated);

        return response()->json($result, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login(
            $request->input('login'),
            $request->input('password')
        );

        return response()->json($result);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Выход выполнен']);
    }
}
