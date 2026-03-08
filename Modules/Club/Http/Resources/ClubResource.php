<?php

namespace Modules\Club\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Reference\Http\Resources\RefItemResource;

class ClubResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'club_type_id'  => $this->club_type_id,
            'sport_type_id' => $this->sport_type_id,
            'country_id'    => $this->country_id,
            'city_id'       => $this->city_id,
            'address'       => $this->address,
            'email'         => $this->email,
            'phones'        => $this->phones,
            'logo_file_id'  => $this->logo_file_id,
            'sport_type'    => new RefItemResource($this->whenLoaded('sportType')),
            'club_type'     => new RefItemResource($this->whenLoaded('clubType')),
            'country'       => new RefItemResource($this->whenLoaded('country')),
            'city'          => new RefItemResource($this->whenLoaded('city')),
            'teams'         => TeamShortResource::collection($this->whenLoaded('teams')),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
        ];
    }
}
