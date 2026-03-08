<?php

namespace Modules\Training\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'country_id' => $this->country_id,
            'city_id'    => $this->city_id,
            'address'    => $this->address,
            'club_id'    => $this->club_id,
        ];
    }
}
