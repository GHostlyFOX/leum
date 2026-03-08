<?php

namespace Modules\Match\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetLineupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'players'                    => 'required|array|min:1',
            'players.*.player_user_id'   => 'required|exists:users,id',
            'players.*.position_id'      => 'required|exists:ref_positions,id',
            'players.*.is_starter'       => 'boolean',
            'players.*.absence_reason'   => 'nullable|string',
            'players.*.parent_user_id'   => 'nullable|exists:users,id',
        ];
    }
}
