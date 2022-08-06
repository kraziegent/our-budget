<?php

namespace Database\Factories;

use App\Enums\AccountType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'currency' => fake()->currencyCode(),
            'type' => fake()->randomElement(AccountType::cases()),
            'is_budget' => fake()->boolean(),
            'account_number' => '1234567890',
        ];
    }
}
