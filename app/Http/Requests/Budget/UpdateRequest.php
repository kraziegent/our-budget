<?php

namespace App\Http\Requests\Budget;

use App\Rules\IsAllowedBudgetStatus;
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
        return $this->user()->can('update', $this->budget);
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
            'status' => ['sometimes', 'nullable', new IsAllowedBudgetStatus],
            'is_default' => ['sometimes', 'nullable', 'boolean'],
        ];
    }
}
