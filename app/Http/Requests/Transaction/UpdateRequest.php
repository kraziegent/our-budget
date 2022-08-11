<?php

namespace App\Http\Requests\Transaction;

use App\Rules\IsAllowedTransactionType;
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
        return $this->user()->can('update', $this->transaction);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => ['sometimes', 'nullable'],
            'account_id' => ['sometimes', 'nullable'],
            'amount' => ['sometimes', 'nullable'],
            'type' => ['sometimes', 'nullable', new IsAllowedTransactionType],
            'payee_id' => ['sometimes', 'nullable'],
            'is_cleared' => ['sometimes', 'nullable'],
            'transaction_date' => ['sometimes', 'nullable'],
            'description' => ['sometimes', 'nullable'],
        ];
    }
}
