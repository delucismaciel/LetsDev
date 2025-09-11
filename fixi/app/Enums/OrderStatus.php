<?php

namespace App\Enums;


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
