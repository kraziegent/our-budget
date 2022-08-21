<?php

namespace App\Rules;

use App\Enums\SharedBudgetStatus;
use Illuminate\Contracts\Validation\Rule;

class IsAllowedSharedBudgetStatus implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        foreach(SharedBudgetStatus::cases() as $type) {
            if ($value == $type->value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid status, kindly select one of the supported status.';
    }
}
