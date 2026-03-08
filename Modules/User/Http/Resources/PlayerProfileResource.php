<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Reference\Http\Resources\RefItemResource;

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
            'user'             => $this->whenLoaded('user', fn () => [
                'id'         => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name'  => $this->user->last_name,
                'birth_date' => $this->user->birth_date,
                'gender'     => $this->user->gender,
            ]),
            'dominant_foot'    => new RefItemResource($this->whenLoaded('dominantFoot')),
            'position'         => new RefItemResource($this->whenLoaded('position')),
            'sport_type'       => new RefItemResource($this->whenLoaded('sportType')),
        ];
    }
}
