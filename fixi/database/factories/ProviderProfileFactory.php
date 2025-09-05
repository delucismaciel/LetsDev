<?php

namespace Database\Factories;

use App\Enums\ProviderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProviderProfile>
 */
class ProviderProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->asProvider(),
            'bio' => fake()->paragraph(3),
            'profile_picture_url' => fake()->imageUrl(400, 400, 'business'),
            'status' => ProviderStatus::APPROVED,
            'average_rating' => fake()->randomFloat(2, 3, 5),
            'total_reviews' => fake()->numberBetween(5, 100),
            'total_orders_completed' => fake()->numberBetween(10, 200),
            'serves_pf' => true,
            'serves_pj' => fake()->boolean(),
        ];
    }
}
