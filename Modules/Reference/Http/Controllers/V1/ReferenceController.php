<?php

namespace Modules\Reference\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
    public function sportTypes(): JsonResponse
    {
        return response()->json(RefSportType::orderBy('name')->get());
    }

    public function clubTypes(): JsonResponse
    {
        return response()->json(RefClubType::orderBy('name')->get());
    }

    public function userRoles(): JsonResponse
    {
        return response()->json(RefUserRole::orderBy('name')->get());
    }

    public function positions(Request $request): JsonResponse
    {
        $query = RefPosition::with('sportType');

        if ($request->filled('sport_type_id')) {
            $query->where('sport_type_id', $request->input('sport_type_id'));
        }

        return response()->json($query->orderBy('name')->get());
    }

    public function dominantFeet(): JsonResponse
    {
        return response()->json(RefDominantFoot::orderBy('name')->get());
    }

    public function kinshipTypes(): JsonResponse
    {
        return response()->json(RefKinshipType::orderBy('name')->get());
    }

    public function matchEventTypes(): JsonResponse
    {
        return response()->json(RefMatchEventType::orderBy('name')->get());
    }

    public function countries(): JsonResponse
    {
        return response()->json(Country::orderBy('name')->get());
    }

    public function cities(Request $request): JsonResponse
    {
        $query = City::with('country');

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }

        return response()->json($query->orderBy('name')->get());
    }
}
