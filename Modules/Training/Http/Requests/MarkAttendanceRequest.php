<?php

namespace Modules\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendance_status' => 'required|in:pending,present,absent',
            'absence_reason'    => 'nullable|string',
        ];
    }
}
