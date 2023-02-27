<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentTeacherFormRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'position' => ['sometimes', 'string', 'max:255'],
            'degree' => ['sometimes', 'string', 'max:255'],
            'department_id' => ['required', 'integer', 'exists:App\Models\Department,id'],
        ];
    }
}
