<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleGetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'group_id' => ['sometimes', 'integer', 'exists:App\Models\Group,id'],
            'teacher_id' => ['sometimes', 'integer', 'exists:App\Models\Teacher,id'],
            'repeatability' => ['sometimes', 'integer'],
            'date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'week' => ['required_without:date', 'string', 'in:current,next']
        ];
    }
}
