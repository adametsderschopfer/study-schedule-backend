<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentGroupFormRequest extends FormRequest
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
            'sub_group' => ['required', 'integer'],
            'degree' => ['required', 'integer'],
            'year_of_education' => ['required', 'integer'],
            'form_of_education' => ['required', 'integer'],
            'department_id' => ['required', 'integer', 'exists:App\Models\Department,id'],
        ];
    }
}
