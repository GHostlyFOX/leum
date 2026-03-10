<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecurringTrainingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'team_id' => $this->team_id,
            'team' => $this->whenLoaded('team', fn () => [
                'id' => $this->team->id,
                'name' => $this->team->name,
            ]),
            'venue_id' => $this->venue_id,
            'venue' => $this->whenLoaded('venue', fn () => [
                'id' => $this->venue->id,
                'name' => $this->venue->name,
            ]),
            'coach_id' => $this->coach_id,
            'coach' => $this->whenLoaded('coach', fn () => [
                'id' => $this->coach->id,
                'name' => $this->coach->short_name,
            ]),
            'schedule' => $this->schedule,
            'auto_create' => $this->auto_create,
            'duration_minutes' => $this->duration_minutes,
            'notify_parents' => $this->notify_parents,
            'require_rsvp' => $this->require_rsvp,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
