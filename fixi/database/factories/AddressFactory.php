<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Pelotas','Pelotas','Pelotas','Pelotas','Pelotas','Pelotas','Pelotas','Rio Grande','Pelotas','Rio Grande','Pelotas','Rio Grande','Pelotas','Rio Grande','Pelotas','Rio Grande','Camaquã','Porto Alegre'];
        return [
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'complement' => $this->faker->optional(0.2)->secondaryAddress(),
            'neighbor' => $this->faker->secondaryAddress(),
            'city' => $this->faker->randomElement($cities),
            'state' => $this->faker->state(),
            'cep' => $this->faker->postcode(),
            'is_main' => $this->faker->boolean(),
            'user_id' => User::inRandomOrder()->first()->id,
            'label' => $this->faker->randomElement(['casa', 'trabalho', 'outro']),
        ];
    }
}
