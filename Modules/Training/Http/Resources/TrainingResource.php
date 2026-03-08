<?php

namespace Modules\Training\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'coach_id'         => $this->coach_id,
            'club_id'          => $this->club_id,
            'team_id'          => $this->team_id,
            'training_date'    => $this->training_date,
            'start_time'       => $this->start_time,
            'duration_minutes' => $this->duration_minutes,
            'venue_id'         => $this->venue_id,
            'training_type_id' => $this->training_type_id,
            'status'           => $this->status,
            'notify_parents'   => $this->notify_parents,
            'require_rsvp'     => $this->require_rsvp,
            'comment'          => $this->comment,
            'recurring_id'     => $this->recurring_id,
            'coach'            => $this->whenLoaded('coach', fn () => [
                'id'         => $this->coach->id,
                'first_name' => $this->coach->first_name,
                'last_name'  => $this->coach->last_name,
            ]),
            'venue'            => new VenueResource($this->whenLoaded('venue')),
            'training_type'    => $this->whenLoaded('trainingType', fn () => [
                'id'   => $this->trainingType->id,
                'name' => $this->trainingType->name,
            ]),
            'attendance'       => TrainingAttendanceResource::collection($this->whenLoaded('attendances')),
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
