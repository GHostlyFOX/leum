<?php

namespace Modules\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'gender'        => 'sometimes|in:boys,girls,mixed',
            'birth_year'    => 'sometimes|integer|min:2000|max:2030',
            'sport_type_id' => 'sometimes|exists:ref_sport_types,id',
            'country_id'    => 'nullable|exists:countries,id',
            'city_id'       => 'nullable|exists:cities,id',
        ];
    }
}
