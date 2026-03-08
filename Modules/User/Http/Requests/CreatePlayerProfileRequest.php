<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dominant_foot_id' => 'required|exists:ref_dominant_feet,id',
            'position_id'      => 'nullable|exists:ref_positions,id',
            'sport_type_id'    => 'required|exists:ref_sport_types,id',
        ];
    }
}
