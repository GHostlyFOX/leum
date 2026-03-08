<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShortResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'middle_name'      => $this->middle_name,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'birth_date'       => $this->birth_date,
            'gender'           => $this->gender,
            'global_role'      => $this->global_role,
            'notifications_on' => $this->notifications_on,
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
