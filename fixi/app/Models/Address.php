<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'number',
        'complement',
        'neighbor',
        'city',
        'state',
        'cep',
        'is_main',
        'label',
        'user_id',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $addressParts = [
                    $this->street,
                    $this->number,
                ];

                if ($this->complement) {
                    $addressParts[] = $this->complement;
                }

                $addressParts[] = $this->neighbor;
                $addressParts[] = "{$this->city} - {$this->state}";
                $addressParts[] = "CEP: {$this->cep}";

                return implode(', ', array_filter($addressParts));
            }
        );
    }

    protected function formattedCep(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => preg_replace('/(\d{5})(\d{3})/', '$1-$2', $attributes['cep']),
        );
    }
}
