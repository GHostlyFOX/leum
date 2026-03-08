<?php

namespace Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coach_id'         => 'sometimes|exists:users,id',
            'training_date'    => 'sometimes|date',
            'start_time'       => 'sometimes|date_format:H:i',
            'duration_minutes' => 'sometimes|integer|min:15|max:300',
            'venue_id'         => 'sometimes|exists:venues,id',
            'training_type_id' => 'sometimes|exists:ref_training_types,id',
            'notify_parents'   => 'boolean',
            'require_rsvp'     => 'boolean',
            'comment'          => 'nullable|string',
        ];
    }
}
