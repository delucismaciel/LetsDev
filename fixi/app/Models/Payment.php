<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'client_id',
        'provider_id',
        'amount',
        'getway',
        'getway_transaction_id',
        'status',
        'payment_method_details',
        'getway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => PaymentStatus::class,
        'payment_method_details' => 'array',
    ];

    protected $appends = [
        'payment_link',
    ];

    public function getPaymentLinkAttribute(): ?string
    {
        // Se não houver um ID de transação, retorna nulo para não gerar um link quebrado
        if (empty($this->getway_transaction_id)) {
            return null;
        }

        // Concatena a URL base com o ID da transação
        return "https://fakepay.com.br/pay/" . $this->getway_transaction_id;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}

