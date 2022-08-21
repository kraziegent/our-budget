<?php

namespace App\Http\Requests\Budget;

use App\Rules\IsAllowedSharedBudgetStatus;
use Illuminate\Foundation\Http\FormRequest;

class ShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('share', $this->budget);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required'],
            'status' => ['sometimes', 'string', new IsAllowedSharedBudgetStatus]
        ];
    }
}
