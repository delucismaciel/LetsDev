<?php

namespace Database\Factories;

use App\Enums\PayoutStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payout>
 */
class PayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        return [
            'provider_id' => User::factory()->asProvider(),
            'amount' => fake()->randomFloat(2, 150, 2500), // Valor do repasse
            'getway' => 'stripe', // Gateway de pagamento padrão
            'getway_transaction_id' => 'po_' . Str::random(24), // ID de transação de Payout simulado
            'status' => PayoutStatus::SUCCEEDED, // Status padrão de repasse bem-sucedido
            'requested_at' => now()->subDay(), // Data da solicitação
            'completed_at' => now(), // Data da conclusão
            'getway_transfer_id' => 'tr_' . Str::random(24), // ID de transferência simulado
        ];
    }

    /**
     * Indica que o repasse está pendente.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PayoutStatus::PENDING,
            'completed_at' => null,
            'getway_transfer_id' => null,
        ]);
    }

    /**
     * Indica que o repasse falhou.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PayoutStatus::FAILED,
            'completed_at' => now(), // A tentativa foi feita, mas falhou
            'getway_transfer_id' => null,
        ]);
    }
}
