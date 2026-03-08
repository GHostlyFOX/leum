<?php

namespace Modules\Match\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'match_type'            => 'required|in:friendly,tournament_group,tournament_playoff',
            'tournament_id'         => 'nullable|exists:tournaments,id',
            'sport_type_id'         => 'required|exists:ref_sport_types,id',
            'venue_id'              => 'required|exists:venues,id',
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'club_id'               => 'required|exists:clubs,id',
            'team_id'               => 'required|exists:teams,id',
            'opponent_team_id'      => 'nullable|exists:teams,id',
            'opponent_id'           => 'nullable|exists:opponents,id',
            'scheduled_at'          => 'required|date',
            'half_duration_minutes' => 'required|integer|min:5|max:60',
            'halves_count'          => 'required|integer|min:1|max:4',
            'is_away'               => 'boolean',
        ];
    }
}
