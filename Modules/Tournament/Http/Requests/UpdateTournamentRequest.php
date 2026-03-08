<?php

namespace Modules\Tournament\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTournamentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tournament_type_id'    => 'sometimes|exists:ref_tournament_types,id',
            'name'                  => 'sometimes|string|max:255',
            'starts_at'             => 'sometimes|date',
            'ends_at'               => 'sometimes|date',
            'half_duration_minutes' => 'sometimes|integer|min:5|max:60',
            'halves_count'          => 'sometimes|integer|min:1|max:4',
            'organizer'             => 'nullable|string|max:255',
        ];
    }
}
