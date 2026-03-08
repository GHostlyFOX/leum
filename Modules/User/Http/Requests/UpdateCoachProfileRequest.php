<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoachProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sport_type_id'   => 'sometimes|exists:ref_sport_types,id',
            'specialty_id'    => 'nullable|exists:ref_positions,id',
            'career_start'    => 'nullable|date',
            'license_number'  => 'nullable|string|max:100',
            'license_expires' => 'nullable|date',
            'achievements'    => 'nullable|array',
        ];
    }
}
