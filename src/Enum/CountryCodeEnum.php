<?php

namespace App\Enum;

enum CountryCodeEnum: string
{
    case GERMANY = 'DE';
    case ITALY = 'IT';
    case GREECE = 'GR';
    case FRANCE = 'FR';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}