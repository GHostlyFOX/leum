<?php

namespace Modules\Match\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMatchEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_type_id'     => 'required|exists:ref_match_event_types,id',
            'match_minute'      => 'required|integer|min:0',
            'player_user_id'    => 'required|exists:users,id',
            'assistant_user_id' => 'nullable|exists:users,id',
        ];
    }
}
