<?php

namespace Modules\Tournament\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'club_id' => 'required|exists:clubs,id',
            'team_id' => 'required|exists:teams,id',
        ];
    }
}
