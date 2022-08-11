<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amount' => makeMoney('5000', 'NGN'),
            'type' => fake()->randomElement(TransactionType::cases()),
            'is_cleared' => fake()->boolean(),
            'transaction_date' => now(),
            'description' => fake()->sentence(),
        ];
    }
}
