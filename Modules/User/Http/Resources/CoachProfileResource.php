<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        ];
    }
}
