<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleGetByPeriodRequest extends FormRequest
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
            'date_start' => ['required', 'date', 'date_format:Y-m-d'],
            'date_end' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:date_start'],
            'group_id' => ['sometimes', 'integer', 'exists:App\Models\Group,id'],
            'teacher_id' => ['sometimes', 'integer', 'exists:App\Models\Teacher,id'],
            'building_id' => ['sometimes', 'integer', 'exists:App\Models\Building,id'],
            'building_classroom_id' => ['sometimes', 'string', 'exists:App\Models\BuildingClassroom,id'],
            'repeatability' => ['sometimes', 'integer']
        ];
    }
}
