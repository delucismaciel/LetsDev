<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'document_type',
        'document',
        'profile_picture_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => Role::class,
    ];

    public function clientProfile(): HasOne
    {
        return $this->hasOne(ClientProfile::class);
    }

    public function providerProfile(): HasOne
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function mainAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'user_id')
            ->where('is_main', true);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function clientJobs(): HasMany
    {
        return $this->hasMany(Order::class, 'client_user_id');
    }

    public function providerJobs(): HasMany
    {
        return $this->hasMany(Order::class, 'provider_user_id');
    }

    public function orderQuotes(): HasMany
    {
        return $this->hasMany(OrderQuote::class, 'provider_user_id');
    }

    public function offeredServices(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'provider_services', 'provider_user_id', 'service_id')
            ->withPivot('base_price', 'description')
            ->withTimestamps();
    }

    //Serviços prestados
    public function services(): HasMany
    {
        return $this->hasMany(Order::class, 'provider_id');
    }

    public function paymentsMade(): HasMany
    {
        return $this->hasMany(Payment::class, 'client_user_id');
    }

    public function payoutsReceived(): HasMany
    {
        return $this->hasMany(Payout::class, 'provider_user_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_user_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function isClient(): bool
    {
        return $this->role === Role::CLIENT;
    }

    public function isProvider(): bool
    {
        return $this->role === Role::PROVIDER;
    }


    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

}
