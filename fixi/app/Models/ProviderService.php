<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProviderService extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'provider_services';

    protected $fillable = [
        'service_id',
        'provider_id',
        'base_price',
        'description',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];
}
