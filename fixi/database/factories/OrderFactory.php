<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providerFee = fake()->randomFloat(2, 100, 1000);
        $platformFee = $providerFee * 0.15;
        $finalPrice = $providerFee + $platformFee;

        return [
            'user_id' => User::factory()->asClient(),
            'service_id' => Service::factory(),
            'provider_id' => User::factory()->asProvider(),
            'address_id' => Address::factory(),
            'title' => 'Serviço de ' . fake()->word(),
            'description' => fake()->paragraph(),
            //Algum status aleatório da enum OrderStatus
            'status' => fake()->randomElement(OrderStatus::cases())->value,
            'started_at' => null,
            'completed_at' => null,
            'provider_fee' => $providerFee,
            'tax_fee' => fake()->randomFloat(2, 2, 10),
            'platform_fee' => $platformFee,
            'final_price' => $finalPrice,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::COMPLETED,
            'started_at' => now()->subHours(2),
            'completed_at' => now(),
        ]);
    }
}
