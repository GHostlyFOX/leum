<?php

namespace Modules\User\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\CreateCoachProfileRequest;
use Modules\User\Http\Requests\CreatePlayerProfileRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Http\Resources\CoachProfileResource;
use Modules\User\Http\Resources\PlayerProfileResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Services\UserService;

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

        return UserResource::collection($users)->response();
    }

    /**
     * GET /api/v1/users/{id}
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->find($id);

        return (new UserResource($user))->response();
    }

    /**
     * GET /api/v1/me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $this->userService->find($request->user()->id);

        return response()->json([
            'user'        => new UserResource($user),
            'role'        => $user->global_role,
            'permissions' => $user->getAllPermissions()->values(),
        ]);
    }

    /**
     * PUT /api/v1/users/{id}
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user    = $this->userService->find($id);
        $updated = $this->userService->update($user, $request->validated());

        return (new UserResource($updated))->response();
    }

    /**
     * POST /api/v1/users/{id}/player-profile
     */
    public function createPlayerProfile(CreatePlayerProfileRequest $request, int $id): JsonResponse
    {
        $profile = $this->userService->createPlayerProfile($id, $request->validated());

        return (new PlayerProfileResource($profile))->response()->setStatusCode(201);
    }

    /**
     * POST /api/v1/users/{id}/coach-profile
     */
    public function createCoachProfile(CreateCoachProfileRequest $request, int $id): JsonResponse
    {
        $profile = $this->userService->createCoachProfile($id, $request->validated());

        return (new CoachProfileResource($profile))->response()->setStatusCode(201);
    }
}
