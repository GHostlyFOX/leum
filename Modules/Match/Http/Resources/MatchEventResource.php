<?php

namespace Modules\Match\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchEventResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'match_id'          => $this->match_id,
            'event_type_id'     => $this->event_type_id,
            'match_minute'      => $this->match_minute,
            'player_user_id'    => $this->player_user_id,
            'assistant_user_id' => $this->assistant_user_id,
            'event_at'          => $this->event_at,
            'created_at'        => $this->created_at,
        ];
    }
}
