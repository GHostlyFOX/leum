<?php

namespace Modules\Club\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Club\Services\ClubService;

class ClubController extends Controller
{
    public function __construct(
        private readonly ClubService $clubService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $clubs = $this->clubService->list(
            filters: $request->only(['sport_type_id', 'country_id', 'city_id', 'search']),
            perPage: $request->integer('per_page', 15),
        );

        return response()->json($clubs);
    }

    public function show(int $id): JsonResponse
    {
        $club = $this->clubService->find($id);
        return response()->json($club);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'club_type_id'  => 'required|exists:ref_club_types,id',
            'sport_type_id' => 'required|exists:ref_sport_types,id',
            'country_id'    => 'required|exists:countries,id',
            'city_id'       => 'required|exists:cities,id',
            'address'       => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phones'        => 'nullable|array',
            'logo'          => 'nullable|image|max:2048',
        ]);

        $club = $this->clubService->create(
            data: collect($validated)->except('logo')->toArray(),
            logo: $request->file('logo'),
        );

        return response()->json($club, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $club = $this->clubService->find($id);

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'club_type_id'  => 'sometimes|exists:ref_club_types,id',
            'sport_type_id' => 'sometimes|exists:ref_sport_types,id',
            'country_id'    => 'sometimes|exists:countries,id',
            'city_id'       => 'sometimes|exists:cities,id',
            'address'       => 'sometimes|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phones'        => 'nullable|array',
            'logo'          => 'nullable|image|max:2048',
        ]);

        $updated = $this->clubService->update(
            club: $club,
            data: collect($validated)->except('logo')->toArray(),
            logo: $request->file('logo'),
        );

        return response()->json($updated);
    }

    public function destroy(int $id): JsonResponse
    {
        $club = $this->clubService->find($id);
        $this->clubService->delete($club);

        return response()->json(null, 204);
    }
}
