<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\RefClubType;
use App\Models\RefDominantFoot;
use App\Models\RefKinshipType;
use App\Models\RefMatchEventType;
use App\Models\RefPosition;
use App\Models\RefSportType;
use App\Models\RefUserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Контроллер для справочных данных.
 * Все эндпоинты доступны без авторизации (публичные справочники).
 */
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

        if ($request->has('sport_type_id')) {
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

        if ($request->has('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }

        return response()->json($query->orderBy('name')->get());
    }
}
