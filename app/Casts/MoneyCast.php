<?php

namespace App\Casts;

use Money\Money;
use Money\Currency;
use InvalidArgumentException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value to a Money Object
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return Money
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value === null) {
            return $value;
        }

        $money = json_decode($value, true);

        return new Money(
            $money['amount'],
            new Currency($money['currency'])
        );
    }

    /**
     * Prepare the money value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  Money $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, $key, $value, $attributes)
    {
        if ($value === null) {
            return [$key => $value];
        }

        if (!$value instanceof Money) {
            throw new InvalidArgumentException(sprintf('Invalid data provided for %s::$%s', get_class($model), $key));
        }

        return [
            $key => json_encode($value),
        ];
    }
}
