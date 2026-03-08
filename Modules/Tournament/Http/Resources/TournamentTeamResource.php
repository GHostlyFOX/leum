<?php

namespace Modules\Tournament\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TournamentTeamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'tournament_id' => $this->tournament_id,
            'club_id'       => $this->club_id,
            'team_id'       => $this->team_id,
            'status'        => $this->status,
        ];
    }
}
