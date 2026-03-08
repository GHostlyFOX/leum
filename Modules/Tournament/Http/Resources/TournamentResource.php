<?php

namespace Modules\Tournament\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'tournament_type_id'    => $this->tournament_type_id,
            'name'                  => $this->name,
            'logo_file_id'          => $this->logo_file_id,
            'starts_at'             => $this->starts_at,
            'ends_at'               => $this->ends_at,
            'half_duration_minutes' => $this->half_duration_minutes,
            'halves_count'          => $this->halves_count,
            'organizer'             => $this->organizer,
            'tournament_type'       => $this->whenLoaded('tournamentType', fn () => [
                'id'   => $this->tournamentType->id,
                'name' => $this->tournamentType->name,
            ]),
            'teams'                 => TournamentTeamResource::collection($this->whenLoaded('teams')),
            'created_at'            => $this->created_at?->toISOString(),
            'updated_at'            => $this->updated_at?->toISOString(),
        ];
    }
}
