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

