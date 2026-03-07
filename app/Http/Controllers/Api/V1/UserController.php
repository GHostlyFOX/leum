<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * GET /api/v1/users
     */
    public function index(Request $request): JsonResponse
    {
        $users = $this->userService->list(
            filters: $request->only(['search']),
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($users);
    }

    /**
     * GET /api/v1/users/{id}
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->find($id);
        return response()->json($user);
    }

    /**
     * GET /api/v1/me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $this->userService->find($request->user()->id);
        return response()->json($user);
    }

    /**
     * PUT /api/v1/users/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->find($id);

        $validated = $request->validate([
            'first_name'       => 'sometimes|string|max:100',
            'last_name'        => 'sometimes|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'email'            => 'sometimes|email|max:255|unique:users,email,' . $id,
            'phone'            => 'nullable|string|max:30',
            'birth_date'       => 'sometimes|date',
            'gender'           => 'sometimes|in:male,female',
            'notifications_on' => 'boolean',
            'password'         => 'nullable|string|min:8|confirmed',
        ]);

        $updated = $this->userService->update($user, $validated);

        return response()->json($updated);
    }

    /**
     * POST /api/v1/users/{id}/player-profile
     */
    public function createPlayerProfile(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'dominant_foot_id' => 'required|exists:ref_dominant_feet,id',
            'position_id'      => 'nullable|exists:ref_positions,id',
            'sport_type_id'    => 'required|exists:ref_sport_types,id',
        ]);

        $profile = $this->userService->createPlayerProfile($id, $validated);

        return response()->json($profile, 201);
    }

    /**
     * POST /api/v1/users/{id}/coach-profile
     */
    public function createCoachProfile(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'sport_type_id'  => 'required|exists:ref_sport_types,id',
            'specialty_id'   => 'nullable|exists:ref_positions,id',
            'career_start'   => 'nullable|date',
            'license_number' => 'nullable|string|max:100',
            'license_expires' => 'nullable|date',
            'achievements'   => 'nullable|array',
        ]);

        $profile = $this->userService->createCoachProfile($id, $validated);

        return response()->json($profile, 201);
    }
}
