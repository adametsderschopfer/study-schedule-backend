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
            'sub_group' => ['sometimes', 'integer'],
            'degree' => ['sometimes', 'integer'],
            'year_of_education' => ['sometimes', 'integer'],
            'form_of_education' => ['sometimes', 'integer'],
            'department_id' => ['required', 'integer', 'exists:App\Models\Department,id'],
        ];
    }
}
