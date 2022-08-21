<?php

namespace App\Http\Requests\Transaction;

use App\Rules\IsAllowedTransactionType;
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
            'category_id' => ['required', 'string'],
            'account_id' => ['required', 'string'],
            'budget_id' => ['required', 'string'],
            'amount' => ['required'],
            'type' => ['required', new IsAllowedTransactionType],
            'payee_id' => ['required_without:payee_name'],
            'payee_name' => ['required_without:payee_id'],
            'is_cleared' => ['sometimes', 'nullable'],
            'transaction_date' => ['sometimes', 'nullable'],
            'description' => ['sometimes', 'nullable'],
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
            'payee_name.required_without' => 'You must provide either a payee or the name for a new one.',
            'payee_id.required_without' => 'You must provide either a payee or the name for a new one.',
        ];
    }
}
