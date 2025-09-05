<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Address;
use App\Models\ClientProfile;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => Role::CLIENT,
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
      public function asClient(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::CLIENT,
        ])->afterCreating(function (User $user) {
            ClientProfile::factory()->for($user)->create();
            Address::factory()->for($user)->create(['is_main' => true]);
        });
    }

    public function asProvider(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::PROVIDER,
        ])->afterCreating(function (User $user) {
            ProviderProfile::factory()->for($user)->create();
        });
    }

    public function asAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN,
        ]);
    }
}
