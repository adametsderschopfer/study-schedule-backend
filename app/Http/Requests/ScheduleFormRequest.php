<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleFormRequest extends FormRequest
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
            'department_id' => ['sometimes', 'integer', 'exists:App\Models\Department,id'],
            'schedule_setting_id' => ['sometimes', 'integer', 'exists:App\Models\ScheduleSetting,id'],
            'department_subject_id' => ['sometimes', 'integer', 'exists:App\Models\DepartmentSubject,id'],
            'teacher_id' => ['sometimes', 'integer', 'exists:App\Models\Teacher,id'],
            'shedule_setting_item_order' => ['required', 'integer', 'max:99'],
            'day_of_week' => ['required', 'integer', 'max:6'],
            'repeatability' => ['sometimes', 'integer'],
            'type' => ['sometimes', 'integer'],
            'sub_group' => ['sometimes', 'integer'],
            'repeat_start' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'repeat_end' => ['sometimes', 'date', 'date_format:Y-m-d', 'after_or_equal:repeat_start'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'repeat_end.after_or_equal' => __('DateEnd after DateStart'),
            'repeat_start.date_format' => __('Date not format'),
            'repeat_end.date_format' => __('Date not format'),
        ];
    }
}
