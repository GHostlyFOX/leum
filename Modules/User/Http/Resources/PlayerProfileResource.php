<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'dominant_foot_id' => $this->dominant_foot_id,
            'position_id'      => $this->position_id,
            'sport_type_id'    => $this->sport_type_id,
        ];
    }
}
