<?php

namespace Modules\Match\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchPlayerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'match_id'        => $this->match_id,
            'club_id'         => $this->club_id,
            'team_id'         => $this->team_id,
            'player_user_id'  => $this->player_user_id,
            'position_id'     => $this->position_id,
            'is_starter'      => $this->is_starter,
            'absence_reason'  => $this->absence_reason,
            'parent_user_id'  => $this->parent_user_id,
        ];
    }
}
