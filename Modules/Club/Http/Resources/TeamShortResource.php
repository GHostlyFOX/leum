<?php

namespace Modules\Club\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamShortResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'gender'     => $this->gender,
            'birth_year' => $this->birth_year,
        ];
    }
}
