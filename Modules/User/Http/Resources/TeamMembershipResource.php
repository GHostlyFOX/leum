<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMembershipResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'team_id'   => $this->team_id,
            'club_id'   => $this->club_id,
            'role_id'   => $this->role_id,
            'joined_at' => $this->joined_at,
            'is_active' => $this->is_active,
        ];
    }
}
