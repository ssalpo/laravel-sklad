<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashTransaction>
 */
class CashTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => fake()->numberBetween(1, 2),
            'amount' => fake()->numberBetween(200, 1500),
            'created_by' => 1,
            'created_at' => fake()->dateTimeBetween(now()->startOfMonth(), now()->endOfMonth())
        ];
    }
}
