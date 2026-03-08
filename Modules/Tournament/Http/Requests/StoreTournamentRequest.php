<?php

namespace Modules\Tournament\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTournamentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tournament_type_id'    => 'required|exists:ref_tournament_types,id',
            'name'                  => 'required|string|max:255',
            'starts_at'             => 'required|date',
            'ends_at'               => 'required|date|after_or_equal:starts_at',
            'half_duration_minutes' => 'required|integer|min:5|max:60',
            'halves_count'          => 'required|integer|min:1|max:4',
            'organizer'             => 'nullable|string|max:255',
        ];
    }
}
