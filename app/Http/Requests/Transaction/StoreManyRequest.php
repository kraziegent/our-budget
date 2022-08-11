<?php

namespace App\Http\Requests\Transaction;

use App\Rules\IsAllowedTransactionType;
use Illuminate\Foundation\Http\FormRequest;

class StoreManyRequest extends FormRequest
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
            'transactions.*' => ['array:category_id,account_id,amount,type,payee_id,payee_name,is_cleared,transaction_date,description', 'required'],
            'transactions.*.category_id' => ['required', 'string'],
            'transactions.*.account_id' => ['required', 'string'],
            'transactions.*.amount' => ['required'],
            'transactions.*.type' => ['required', new IsAllowedTransactionType],
            'transactions.*.payee_id' => ['required_without:transactions.*.payee_name', 'string'],
            'transactions.*.payee_name' => ['required_without:transactions.*.payee_id', 'string'],
            'transactions.*.is_cleared' => ['sometimes', 'nullable', 'boolean'],
            'transactions.*.transaction_date' => ['sometimes', 'nullable', 'date'],
            'transactions.*.description' => ['sometimes', 'nullable', 'string'],
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
            'transactions.*.payee_name.required_without' => 'You must provide either a payee or the name for a new one.',
            'transactions.*.payee_id.required_without' => 'You must provide either a payee or the name for a new one.',
        ];
    }
}
