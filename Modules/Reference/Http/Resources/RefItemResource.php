<?php

namespace Modules\Reference\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
