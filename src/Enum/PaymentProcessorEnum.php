<?php

namespace App\Enum;

enum PaymentProcessorEnum: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
