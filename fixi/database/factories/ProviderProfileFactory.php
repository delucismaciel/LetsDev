<?php

namespace Database\Factories;

use App\Enums\ProviderStatus;
use App\Models\Address;
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
        $user = User::where('role', 'provider')->orwhere('role', 'admin')->inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'bio' => fake()->paragraph(random_int(1,4)),
            'profile_picture_url' => fake()->imageUrl(400, 400, 'business'),
            'status' => ProviderStatus::APPROVED,
            'average_rating' => fake()->randomFloat(2, 3, 5),
            'total_reviews' => fake()->numberBetween(5, 100),
            'total_orders_completed' => fake()->numberBetween(10, 200),
            'serves_pf' => true,
            'serves_pj' => fake()->boolean(),
            'address_id' => Address::factory()->for($user)->create(['is_main' => true])->id
        ];
    }
}
