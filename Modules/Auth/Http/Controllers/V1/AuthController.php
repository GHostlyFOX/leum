<?php

namespace Modules\Auth\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RefreshRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Services\AuthService;
use Modules\User\Http\Resources\UserShortResource;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * POST /api/v1/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($this->tokenResponse($result), 201);
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->input('login'),
            $request->input('password')
        );

        return response()->json($this->tokenResponse($result));
    }

    /**
     * POST /api/v1/auth/refresh
     */
    public function refresh(RefreshRequest $request): JsonResponse
    {
        $result = $this->authService->refresh($request->input('refresh_token'));

        return response()->json($this->tokenResponse($result));
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Выход выполнен']);
    }

    /**
     * POST /api/v1/auth/forgot-password
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->input('email'));

        return response()->json([
            'message' => 'Инструкции по сбросу пароля отправлены на email.',
        ]);
    }

    /**
     * POST /api/v1/auth/reset-password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->authService->resetPassword(
            $request->input('email'),
            $request->input('token'),
            $request->input('password')
        );

        return response()->json([
            'message' => 'Пароль успешно сброшен.',
        ]);
    }

    // ────────────────────────────────────────────────────────────

    private function tokenResponse(array $result): array
    {
        return [
            'user'          => new UserShortResource($result['user']),
            'access_token'  => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'token_type'    => 'Bearer',
            'expires_in'    => $result['expires_in'],
            'permissions'   => $result['permissions'],
        ];
    }
}
