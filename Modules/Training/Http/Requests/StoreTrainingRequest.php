<?php

namespace Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coach_id'         => 'required|exists:users,id',
            'club_id'          => 'required|exists:clubs,id',
            'team_id'          => 'required|exists:teams,id',
            'training_date'    => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:300',
            'venue_id'         => 'required|exists:venues,id',
            'training_type_id' => 'required|exists:ref_training_types,id',
            'notify_parents'   => 'boolean',
            'require_rsvp'     => 'boolean',
            'comment'          => 'nullable|string',
        ];
    }
}
