<?php

namespace Modules\Training\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VenueResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Training\Models\Venue;

class VenueController extends Controller
{
    /**
     * Список мест проведения
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'club_id' => 'nullable|integer|exists:clubs,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'search' => 'nullable|string|max:255',
        ]);

        $query = Venue::with(['country', 'city', 'club']);

        if ($request->has('club_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('club_id', $request->club_id)
                  ->orWhereNull('club_id');
            });
        }

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        $venues = $query->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => VenueResource::collection($venues),
            'meta' => [
                'current_page' => $venues->currentPage(),
                'last_page' => $venues->lastPage(),
                'per_page' => $venues->perPage(),
                'total' => $venues->total(),
            ],
        ]);
    }

    /**
     * Создать место проведения
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $venue = Venue::create($validated);
        $venue->load(['country', 'city', 'club']);

        return response()->json([
            'data' => new VenueResource($venue),
            'message' => 'Место проведения создано',
        ], 201);
    }

    /**
     * Получить место проведения
     */
    public function show(int $id): JsonResponse
    {
        $venue = Venue::with(['country', 'city', 'club'])->findOrFail($id);

        return response()->json([
            'data' => new VenueResource($venue),
        ]);
    }

    /**
     * Обновить место проведения
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $venue = Venue::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|nullable|string|max:500',
            'country_id' => 'sometimes|integer|exists:countries,id',
            'city_id' => 'sometimes|integer|exists:cities,id',
            'club_id' => 'sometimes|nullable|integer|exists:clubs,id',
            'description' => 'sometimes|nullable|string|max:1000',
        ]);

        $venue->update($validated);
        $venue->load(['country', 'city', 'club']);

        return response()->json([
            'data' => new VenueResource($venue),
            'message' => 'Место проведения обновлено',
        ]);
    }

    /**
     * Удалить место проведения
     */
    public function destroy(int $id): JsonResponse
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return response()->json(null, 204);
    }
}
