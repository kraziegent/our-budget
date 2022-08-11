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
            'category_id' => ['required'],
            'account_id' => ['required'],
            'amount' => ['required'],
            'type' => ['required', new IsAllowedTransactionType],
            'payee_id' => ['required_without:payee_name'],
            'payee_name' => ['required_without:payee_id'],
            'is_cleared' => ['sometimes', 'nullable'],
            'transaction_date' => ['sometimes', 'nullable'],
            'description' => ['sometimes', 'nullable'],
        ];
    }
}
