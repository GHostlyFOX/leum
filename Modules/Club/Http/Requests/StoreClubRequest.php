<?php

namespace Modules\Club\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'club_type_id'  => 'required|exists:ref_club_types,id',
            'sport_type_id' => 'required|exists:ref_sport_types,id',
            'country_id'    => 'required|exists:countries,id',
            'city_id'       => 'required|exists:cities,id',
            'address'       => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phones'        => 'nullable|array',
            'logo'          => 'nullable|image|max:2048',
        ];
    }
}
