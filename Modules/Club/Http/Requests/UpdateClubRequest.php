<?php

namespace Modules\Club\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClubRequest extends FormRequest
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
            'club_type_id'  => 'sometimes|exists:ref_club_types,id',
            'sport_type_id' => 'sometimes|exists:ref_sport_types,id',
            'country_id'    => 'sometimes|exists:countries,id',
            'city_id'       => 'sometimes|exists:cities,id',
            'address'       => 'sometimes|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phones'        => 'nullable|array',
            'logo'          => 'nullable|image|max:2048',
        ];
    }
}
