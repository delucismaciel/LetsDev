<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        // Cria um pedido para associar o pagamento, garantindo que os dados sejam consistentes.
        $order = Order::factory()->create();

        return [
            'order_id' => $order->id,
            'client_id' => $order->user_id, // O cliente vem do pedido
            'provider_id' => $order->provider_id, // O prestador vem do pedido
            'amount' => $order->final_price, // O valor do pagamento é o preço final do pedido
            'getway' => 'stripe', // Gateway de pagamento padrão para testes
            'getway_transaction_id' => 'pi_' . Str::random(24), // ID de transação simulado (Payment Intent)
            'status' => PaymentStatus::SUCCEEDED, // Status padrão de pagamento bem-sucedido
            'payment_method_details' => [
                'type' => 'card',
                'card_brand' => fake()->randomElement(['visa', 'mastercard']),
                'last4' => fake()->numerify('####'),
            ],
            'getway_response' => ['status' => 'succeeded', 'message' => 'Pagamento aprovado.'],
        ];
    }

    /**
     * Indica que o pagamento falhou.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::FAILED,
            'getway_response' => ['status' => 'failed', 'message' => 'Pagamento recusado pelo emissor do cartão.'],
        ]);
    }

    /**
     * Indica que o pagamento está pendente.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::PENDING,
            'getway_response' => ['status' => 'pending', 'message' => 'Aguardando confirmação do pagamento.'],
        ]);
    }
}
