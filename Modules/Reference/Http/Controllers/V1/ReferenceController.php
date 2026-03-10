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
use Modules\Reference\Models\RefMatchEventGroup;
use Modules\Reference\Models\RefMatchEventType;
use Modules\Reference\Models\RefPosition;
use Modules\Reference\Models\RefSportType;
use Modules\Reference\Models\RefTournamentType;
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
     * GET /api/v1/refs/match-event-groups
     */
    public function matchEventGroups(Request $request): JsonResponse
    {
        $query = RefMatchEventGroup::query()
            ->orderBy('sort_order')
            ->orderBy('name');

        // Фильтр по виду спорта (показываем общие группы + специфичные для вида спорта)
        if ($request->filled('sport_type_id')) {
            $sportTypeId = $request->input('sport_type_id');
            $query->where(function ($q) use ($sportTypeId) {
                $q->whereNull('sport_type_id')
                  ->orWhere('sport_type_id', $sportTypeId);
            });
        }

        $groups = $query->get();

        return response()->json([
            'data' => $groups->map(fn ($g) => [
                'id'            => $g->id,
                'name'          => $g->name,
                'code'          => $g->code,
                'icon'          => $g->icon,
                'color'         => $g->color,
                'sort_order'    => $g->sort_order,
                'sport_type_id' => $g->sport_type_id,
            ]),
        ]);
    }

    /**
     * GET /api/v1/refs/match-event-types
     */
    public function matchEventTypes(Request $request): JsonResponse
    {
        $query = RefMatchEventType::query()
            ->with('group')
            ->orderBy('sort_order')
            ->orderBy('name');

        // Фильтр по виду спорта
        if ($request->filled('sport_type_id')) {
            $sportTypeId = $request->input('sport_type_id');
            $query->where(function ($q) use ($sportTypeId) {
                $q->whereNull('sport_type_id')
                  ->orWhere('sport_type_id', $sportTypeId);
            });
        }

        // Фильтр по группе
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->input('group_id'));
        }

        $types = $query->get();

        return response()->json([
            'data' => $types->map(fn ($t) => [
                'id'             => $t->id,
                'name'           => $t->name,
                'code'           => $t->code,
                'group_id'       => $t->group_id,
                'sport_type_id'  => $t->sport_type_id,
                'sort_order'     => $t->sort_order,
                'is_statistical' => $t->is_statistical,
                'affects_score'  => $t->affects_score,
                'icon'           => $t->icon,
                'color'          => $t->color,
            ]),
        ]);
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

    /**
     * GET /api/v1/refs/tournament-types
     */
    public function tournamentTypes(Request $request): JsonResponse
    {
        $query = RefTournamentType::query();

        if ($request->filled('sport_type_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('sport_type_id')
                  ->orWhere('sport_type_id', $request->input('sport_type_id'));
            });
        }

        $types = $query->orderBy('name')->get();

        return response()->json([
            'data' => $types->map(fn ($t) => [
                'id'            => $t->id,
                'name'          => $t->name,
                'sport_type_id' => $t->sport_type_id,
            ]),
        ]);
    }
}
