<?php

namespace App\Models;

use App\Enums\ProviderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'bio',
        'profile_picture_url',
        'status',
        'average_rating',
        'total_reviews',
        'total_orders',
        'total_orders_completed',
        'serves_pf',
        'serves_pj',
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'serves_pf' => 'boolean',
        'serves_pj' => 'boolean',
        'status' => ProviderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

}
