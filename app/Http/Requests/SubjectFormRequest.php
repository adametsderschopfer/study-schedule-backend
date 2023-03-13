<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Services\AccountService;

class SubjectFormRequest extends FormRequest
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }
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
            'parent_id' => Rule::requiredIf($this->accountService->getType() !== Account::TYPES['SCHOOL']),
        ];
    }
}
