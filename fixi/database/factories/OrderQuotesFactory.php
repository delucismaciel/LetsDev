<?php

namespace Database\Factories;

use App\Enums\QuoteStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderQuotes>
 */
class OrderQuotesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider_id' => User::factory()->asProvider(),
            'price' => fake()->randomFloat(2, 100, 1000),
            'description' => fake()->sentence(),
            'accepted' => false,
            'estimated_time' => fake()->numberBetween(1, 8),
            'estimated_time_unit' => 'hours',
            'status' => fake()->randomElement(QuoteStatus::cases())->value,
            'expires_at' => now()->addDays(7),
        ];
    }
}
