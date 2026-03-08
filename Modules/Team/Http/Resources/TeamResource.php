<?php

namespace Modules\Team\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'gender'        => $this->gender,
            'birth_year'    => $this->birth_year,
            'club_id'       => $this->club_id,
            'sport_type_id' => $this->sport_type_id,
            'country_id'    => $this->country_id,
            'city_id'       => $this->city_id,
            'logo_file_id'  => $this->logo_file_id,
            'club'          => $this->whenLoaded('club', fn () => [
                'id'   => $this->club->id,
                'name' => $this->club->name,
            ]),
            'members'       => TeamMemberResource::collection($this->whenLoaded('members')),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
        ];
    }
}
