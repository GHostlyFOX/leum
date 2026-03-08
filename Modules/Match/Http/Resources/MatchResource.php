<?php

namespace Modules\Match\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Training\Http\Resources\VenueResource;

class MatchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'match_type'            => $this->match_type,
            'tournament_id'         => $this->tournament_id,
            'sport_type_id'         => $this->sport_type_id,
            'venue_id'              => $this->venue_id,
            'name'                  => $this->name,
            'description'           => $this->description,
            'club_id'               => $this->club_id,
            'team_id'               => $this->team_id,
            'opponent_team_id'      => $this->opponent_team_id,
            'opponent_id'           => $this->opponent_id,
            'scheduled_at'          => $this->scheduled_at,
            'half_duration_minutes' => $this->half_duration_minutes,
            'halves_count'          => $this->halves_count,
            'is_away'               => $this->is_away,
            'actual_start_at'       => $this->actual_start_at,
            'actual_end_at'         => $this->actual_end_at,
            'venue'                 => new VenueResource($this->whenLoaded('venue')),
            'opponent'              => $this->whenLoaded('opponent', fn () => [
                'id'   => $this->opponent->id,
                'name' => $this->opponent->name,
            ]),
            'players'               => MatchPlayerResource::collection($this->whenLoaded('players')),
            'events'                => MatchEventResource::collection($this->whenLoaded('events')),
            'created_at'            => $this->created_at?->toISOString(),
            'updated_at'            => $this->updated_at?->toISOString(),
        ];
    }
}
