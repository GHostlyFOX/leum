<?php

namespace Modules\Training\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Training\Models\Announcement;

class AnnouncementController extends Controller
{
    /**
     * Список объявлений
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'club_id' => 'required|integer|exists:clubs,id',
            'team_id' => 'nullable|integer|exists:teams,id',
            'is_draft' => 'nullable|boolean',
            'priority' => 'nullable|in:normal,important,urgent',
        ]);

        $query = Announcement::byClub($request->club_id)
            ->with(['team', 'author']);

        if ($request->has('team_id')) {
            $query->byTeam($request->team_id);
        }

        if ($request->has('is_draft')) {
            if ($request->boolean('is_draft')) {
                $query->drafts();
            } else {
                $query->published();
            }
        }

        if ($request->has('priority')) {
            $query->byPriority($request->priority);
        }

        $announcements = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => AnnouncementResource::collection($announcements),
            'meta' => [
                'current_page' => $announcements->currentPage(),
                'last_page' => $announcements->lastPage(),
                'per_page' => $announcements->perPage(),
                'total' => $announcements->total(),
            ],
        ]);
    }

    /**
     * Создать объявление
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'nullable|in:normal,important,urgent',
            'team_id' => 'nullable|integer|exists:teams,id',
            'club_id' => 'required|integer|exists:clubs,id',
            'is_draft' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['priority'] = $validated['priority'] ?? 'normal';
        $validated['is_draft'] = $validated['is_draft'] ?? true;

        // Если не черновик и не указана дата публикации - публикуем сейчас
        if (!$validated['is_draft'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $announcement = Announcement::create($validated);
        $announcement->load(['team', 'author']);

        return response()->json([
            'data' => new AnnouncementResource($announcement),
            'message' => 'Объявление создано',
        ], 201);
    }

    /**
     * Получить объявление
     */
    public function show(int $id): JsonResponse
    {
        $announcement = Announcement::with(['team', 'author', 'club'])->findOrFail($id);

        return response()->json([
            'data' => new AnnouncementResource($announcement),
        ]);
    }

    /**
     * Обновить объявление
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'message' => 'sometimes|string',
            'priority' => 'sometimes|in:normal,important,urgent',
            'team_id' => 'sometimes|nullable|integer|exists:teams,id',
            'is_draft' => 'sometimes|boolean',
            'published_at' => 'sometimes|nullable|date',
            'expires_at' => 'sometimes|nullable|date|after:now',
        ]);

        // Если публикуем из черновика
        if (isset($validated['is_draft']) && !$validated['is_draft'] && $announcement->is_draft) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);
        $announcement->load(['team', 'author']);

        return response()->json([
            'data' => new AnnouncementResource($announcement),
            'message' => 'Объявление обновлено',
        ]);
    }

    /**
     * Удалить объявление
     */
    public function destroy(int $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return response()->json(null, 204);
    }

    /**
     * Опубликовать объявление
     */
    public function publish(int $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->isPublished()) {
            return response()->json([
                'message' => 'Объявление уже опубликовано',
            ], 422);
        }

        $announcement->publish();
        $announcement->load(['team', 'author']);

        return response()->json([
            'data' => new AnnouncementResource($announcement),
            'message' => 'Объявление опубликовано',
        ]);
    }
}
