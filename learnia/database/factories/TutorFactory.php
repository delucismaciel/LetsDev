<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'tutor']),
            'education' => $this->faker->sentence(5), // Ex: "Bacharel em Ciência da Computação."
            'experience' => $this->faker->sentence(8), // Ex: "5 anos de experiência como desenvolvedor front-end."
            'bio' => $this->faker->paragraph(3), // Gera uma biografia com 3 parágrafos
            'rating' => $this->faker->randomFloat(1, 3.0, 5.0), // Gera uma nota entre 4.0 e 5.0
        ];
    }
}
