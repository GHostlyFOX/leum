<?php

namespace Modules\Team\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'club_id'   => $this->club_id,
            'team_id'   => $this->team_id,
            'role_id'   => $this->role_id,
            'joined_at' => $this->joined_at,
            'is_active' => $this->is_active,
        ];
    }
}
