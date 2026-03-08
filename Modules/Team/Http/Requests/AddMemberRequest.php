<?php

namespace Modules\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'   => 'required|exists:users,id',
            'club_id'   => 'required|exists:clubs,id',
            'role_id'   => 'required|exists:ref_user_roles,id',
            'joined_at' => 'nullable|date',
        ];
    }
}
