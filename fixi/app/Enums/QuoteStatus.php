<?php

namespace App\Enums;

enum QuoteStatus: string
{
    case SENT = 'sent';
    case VIEWED = 'viewed';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
