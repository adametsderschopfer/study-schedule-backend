<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingFormRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'count' => ['sometimes', 'integer', 'max:99'], 
            'setting_items' => ['sometimes', 'array'], 
            'setting_items.*.time_start' => ['sometimes', 'date_format:H:i'],
            'setting_items.*.time_end' => ['sometimes', 'date_format:H:i', 'after_or_equal:setting_items.*.time_start'],
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
            'setting_items.*.time_end.after_or_equal' => __('TimeEnd after TimeStart'),
            'setting_items.*.*.date_format' => __('Time not format 00:00'),
        ];
    }

}
