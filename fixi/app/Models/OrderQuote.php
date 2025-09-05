<?php

namespace App\Models;

use App\Enums\QuoteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider_id',
        'price',
        'description',
        'accepted',
        'estimated_time',
        'estimated_time_unit',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'accepted' => 'boolean',
        'estimated_time' => 'integer',
        'status' => QuoteStatus::class,
        'expires_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
