<?php

namespace App\Http\Requests\Account;

use App\Rules\IsAllowedType;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'type' => ['required', new IsAllowedType],
            'is_budget' => ['required', 'boolean'],
            'account_number' => ['sometimes', 'nullable', 'string'],
            'opening_balance' => ['sometimes', 'nullable', 'numeric'],
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
