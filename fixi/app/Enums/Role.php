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

enum OrderStatus: string
{
    case PENDING_QUOTES = 'pending_quotes';
    case PENDING_PAYMENT = 'pending_payment';
    case SCHEDULED = 'scheduled';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case DISPUTED = 'disputed';
}

enum QuoteStatus: string
{
    case SENT = 'sent';
    case VIEWED = 'viewed';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
}

enum PayoutStatus: string
{
    case PENDING = 'pending';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
}
