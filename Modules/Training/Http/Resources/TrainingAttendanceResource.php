<?php

namespace Modules\Training\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingAttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'training_id'       => $this->training_id,
            'player_user_id'    => $this->player_user_id,
            'marked_by_user_id' => $this->marked_by_user_id,
            'attendance_status' => $this->attendance_status,
            'confirmed_at'      => $this->confirmed_at,
            'absence_reason'    => $this->absence_reason,
            'created_at'        => $this->created_at?->toISOString(),
            'updated_at'        => $this->updated_at?->toISOString(),
        ];
    }
}
