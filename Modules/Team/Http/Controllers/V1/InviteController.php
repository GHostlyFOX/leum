<?php

namespace Modules\Team\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InviteLinkResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Team\Models\InviteLink;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

class InviteController extends Controller
{
    /**
     * Список инвайт-ссылок
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'team_id' => 'nullable|integer|exists:teams,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
        ]);

        $query = InviteLink::with(['team', 'createdBy']);

        if ($request->has('team_id')) {
            $query->byTeam($request->team_id);
        }

        if ($request->has('club_id')) {
            $query->byClub($request->club_id);
        }

        $invites = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => InviteLinkResource::collection($invites),
            'meta' => [
                'current_page' => $invites->currentPage(),
                'last_page' => $invites->lastPage(),
                'per_page' => $invites->perPage(),
                'total' => $invites->total(),
            ],
        ]);
    }

    /**
     * Создать инвайт-ссылку
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'team_id' => 'required|integer|exists:teams,id',
            'role' => 'required|in:player,coach,parent',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'required|date|after:now',
        ]);

        $invite = InviteLink::create([
            'token' => Str::random(32),
            'team_id' => $validated['team_id'],
            'role' => $validated['role'],
            'created_by_id' => auth()->id(),
            'max_uses' => $validated['max_uses'] ?? null,
            'used_count' => 0,
            'expires_at' => $validated['expires_at'],
        ]);

        $invite->load(['team', 'createdBy']);

        return response()->json([
            'data' => new InviteLinkResource($invite),
            'message' => 'Пригласительная ссылка создана',
        ], 201);
    }

    /**
     * Получить инвайт-ссылку
     */
    public function show(int $id): JsonResponse
    {
        $invite = InviteLink::with(['team', 'createdBy'])->findOrFail($id);

        return response()->json([
            'data' => new InviteLinkResource($invite),
        ]);
    }

    /**
     * Удалить инвайт-ссылку
     */
    public function destroy(int $id): JsonResponse
    {
        $invite = InviteLink::findOrFail($id);
        $invite->delete();

        return response()->json(null, 204);
    }

    /**
     * Проверить валидность токена (публичный endpoint)
     */
    public function validateToken(string $token): JsonResponse
    {
        $invite = InviteLink::where('token', $token)
            ->with('team')
            ->first();

        if (!$invite || !$invite->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Ссылка недействительна или истекла',
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'team' => [
                    'id' => $invite->team->id,
                    'name' => $invite->team->name,
                ],
                'role' => $invite->role,
            ],
        ]);
    }

    /**
     * Принять приглашение
     */
    public function accept(string $token, Request $request): JsonResponse
    {
        $invite = InviteLink::where('token', $token)
            ->with('team')
            ->first();

        if (!$invite || !$invite->isValid()) {
            return response()->json([
                'message' => 'Ссылка недействительна или истекла',
            ], 404);
        }

        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Требуется авторизация',
            ], 401);
        }

        // Добавляем пользователя в команду
        $team = $invite->team;

        // Проверяем, не состоит ли уже пользователь в команде
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'Вы уже состоите в этой команде',
            ], 422);
        }

        // Определяем роль
        $roleId = match ($invite->role) {
            'coach'  => 2, // Тренер
            'parent' => 9, // Родитель
            default  => 6, // Игрок
        };

        $team->members()->attach($user->id, [
            'club_id' => $team->club_id,
            'role_id' => $roleId,
            'joined_at' => now(),
            'is_active' => true,
        ]);

        // Увеличиваем счётчик использований
        $invite->incrementUsage();

        return response()->json([
            'message' => 'Вы успешно присоединились к команде',
            'data' => [
                'team' => [
                    'id' => $team->id,
                    'name' => $team->name,
                ],
                'role' => $invite->role,
            ],
        ]);
    }
}
