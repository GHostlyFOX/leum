<?php

namespace Modules\Reference\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Reference\Http\Resources\RefItemResource;
use Modules\Reference\Models\City;
use Modules\Reference\Models\Country;
use Modules\Reference\Models\RefClubType;
use Modules\Reference\Models\RefDominantFoot;
use Modules\Reference\Models\RefKinshipType;
use Modules\Reference\Models\RefMatchEventType;
use Modules\Reference\Models\RefPosition;
use Modules\Reference\Models\RefSportType;
use Modules\Reference\Models\RefUserRole;

class ReferenceController extends Controller
{
    /**
     * GET /api/v1/refs/sport-types
     */
    public function sportTypes(): JsonResponse
    {
        return RefItemResource::collection(
            RefSportType::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/club-types
     */
    public function clubTypes(): JsonResponse
    {
        return RefItemResource::collection(
            RefClubType::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/user-roles
     */
    public function userRoles(): JsonResponse
    {
        return RefItemResource::collection(
            RefUserRole::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/positions
     */
    public function positions(Request $request): JsonResponse
    {
        $query = RefPosition::query();

        if ($request->filled('sport_type_id')) {
            $query->where('sport_type_id', $request->input('sport_type_id'));
        }

        $positions = $query->orderBy('name')->get();

        return response()->json([
            'data' => $positions->map(fn ($p) => [
                'id'            => $p->id,
                'name'          => $p->name,
                'sport_type_id' => $p->sport_type_id,
            ]),
        ]);
    }

    /**
     * GET /api/v1/refs/dominant-feet
     */
    public function dominantFeet(): JsonResponse
    {
        return RefItemResource::collection(
            RefDominantFoot::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/kinship-types
     */
    public function kinshipTypes(): JsonResponse
    {
        return RefItemResource::collection(
            RefKinshipType::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/match-event-types
     */
    public function matchEventTypes(): JsonResponse
    {
        return RefItemResource::collection(
            RefMatchEventType::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/countries
     */
    public function countries(): JsonResponse
    {
        return RefItemResource::collection(
            Country::orderBy('name')->get()
        )->response();
    }

    /**
     * GET /api/v1/refs/cities
     */
    public function cities(Request $request): JsonResponse
    {
        $query = City::query();

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }

        $cities = $query->orderBy('name')->get();

        return response()->json([
            'data' => $cities->map(fn ($c) => [
                'id'         => $c->id,
                'name'       => $c->name,
                'country_id' => $c->country_id,
            ]),
        ]);
    }
}
