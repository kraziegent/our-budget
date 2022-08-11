<?php

namespace App\Http\Requests\Account;

use App\Rules\IsAllowedAccountType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->account);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'nullable', 'string'],
            'currency' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', 'nullable', new IsAllowedAccountType],
            'is_budget' => ['sometimes', 'nullable', 'boolean'],
            'account_number' => ['sometimes', 'nullable', 'string'],
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

        ];
    }
}
