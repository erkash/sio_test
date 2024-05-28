<?php

namespace App\Enum;

enum CouponTypeEnum: string
{
    case FIXED = 'fixed';
    case PERCENT = 'percent';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}