<?php

namespace App\Models;

use App\Enums\PayoutStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'amount',
        'getway',
        'getway_transaction_id',
        'status',
        'requested_at',
        'completed_at',
        'getway_transfer_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => PayoutStatus::class,
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
