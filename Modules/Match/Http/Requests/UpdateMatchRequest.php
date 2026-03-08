<?php

namespace Modules\Match\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venue_id'              => 'sometimes|exists:venues,id',
            'name'                  => 'sometimes|string|max:255',
            'description'           => 'nullable|string',
            'scheduled_at'          => 'sometimes|date',
            'half_duration_minutes' => 'sometimes|integer|min:5|max:60',
            'halves_count'          => 'sometimes|integer|min:1|max:4',
            'is_away'               => 'boolean',
        ];
    }
}
