<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'provider_id',
        'address_id',
        'title',
        'description',
        'status',
        'started_at',
        'completed_at',
        'provider_fee',
        'tax_fee',
        'platform_fee',
        'final_price',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'provider_fee' => 'decimal:2',
        'tax_fee' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    protected $appends = [
        'payment_link',
    ];

    public function getPaymentLinkAttribute(): ?string
    {
        // Acessa a relação 'payment' e, se ela existir, retorna o atributo 'payment_link' do modelo Payment.
        // A função optional() previne erros se a relação não existir (retornando null).
        return optional($this->payment)->payment_link;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
