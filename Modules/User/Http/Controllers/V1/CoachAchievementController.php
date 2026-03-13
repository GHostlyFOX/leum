<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\User\Models\CoachAchievement;
use Modules\User\Models\CoachProfile;

class CoachAchievementController extends Controller
{
    /**
     * Список достижений тренера
     */
    public function index(int $coachId): JsonResponse
    {
        $profile = CoachProfile::with('achievementsList')
            ->where('user_id', $coachId)
            ->firstOrFail();

        return response()->json([
            'data' => $profile->achievementsList,
        ]);
    }

    /**
     * Добавить достижение
     */
    public function store(Request $request, int $coachId): JsonResponse
    {
        $profile = CoachProfile::where('user_id', $coachId)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'category' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $achievement = CoachAchievement::create([
            'coach_profile_id' => $profile->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'year' => $request->input('year'),
            'category' => $request->input('category'),
        ]);

        return response()->json([
            'message' => 'Достижение добавлено',
            'data' => $achievement,
        ], 201);
    }

    /**
     * Обновить достижение
     */
    public function update(Request $request, int $coachId, int $achievementId): JsonResponse
    {
        $profile = CoachProfile::where('user_id', $coachId)->firstOrFail();
        
        $achievement = CoachAchievement::where('coach_profile_id', $profile->id)
            ->where('id', $achievementId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'year' => 'sometimes|integer|min:1950|max:' . (date('Y') + 1),
            'category' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $achievement->update($request->only(['title', 'description', 'year', 'category']));

        return response()->json([
            'message' => 'Достижение обновлено',
            'data' => $achievement,
        ]);
    }

    /**
     * Удалить достижение
     */
    public function destroy(int $coachId, int $achievementId): JsonResponse
    {
        $profile = CoachProfile::where('user_id', $coachId)->firstOrFail();
        
        $achievement = CoachAchievement::where('coach_profile_id', $profile->id)
            ->where('id', $achievementId)
            ->firstOrFail();

        $achievement->delete();

        return response()->json([
            'message' => 'Достижение удалено',
        ]);
    }
}
