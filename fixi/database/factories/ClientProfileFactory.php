<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientProfile>
 */
class ClientProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'user_id' => User::factory(),
            'trade_name' => fake()->company(),
            'profile_picture_url' => fake()->imageUrl(400, 400, 'people'),
            'document_type' => 'CPF',
            'document' => fake('pt_BR')->cpf(false),
        ];
    }
}
