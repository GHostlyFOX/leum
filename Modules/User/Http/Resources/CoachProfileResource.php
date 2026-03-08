<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Reference\Http\Resources\RefItemResource;

class CoachProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'sport_type_id'   => $this->sport_type_id,
            'specialty_id'    => $this->specialty_id,
            'career_start'    => $this->career_start,
            'license_number'  => $this->license_number,
            'license_expires' => $this->license_expires,
            'achievements'    => $this->achievements,
            'user'            => $this->whenLoaded('user', fn () => [
                'id'         => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name'  => $this->user->last_name,
            ]),
            'sport_type'      => new RefItemResource($this->whenLoaded('sportType')),
            'specialty'       => new RefItemResource($this->whenLoaded('specialty')),
        ];
    }
}
