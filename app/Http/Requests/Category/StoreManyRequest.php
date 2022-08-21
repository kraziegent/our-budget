<?php

namespace App\Http\Requests\Category;

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
            'categories.*' => ['array:name,master_category_name,master_category_id', 'required'],
            'categories.*.name' => ['required', 'string'],
            'budget_id' => ['required', 'string'],
            'categories.*.master_category_name' => ['required_without:categories.*.master_category_id'],
            'categories.*.master_category_id' => ['required_without:categories.*.master_category_name'],
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
            'categories.*.master_category_name.required_without' => 'You must provide either a previous master category or the name for a new one.',
            'categories.*.master_category_id.required_without' => 'You must provide either a previous master category or the name for a new one.',
        ];
    }
}
