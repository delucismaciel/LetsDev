<?php

namespace App\Enums;

enum Role: string
{
    case CLIENT = 'client';
    case PROVIDER = 'provider';
    case ADMIN = 'admin';
}

enum ProviderStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
