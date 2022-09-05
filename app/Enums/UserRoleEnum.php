<?php

namespace App\Enums;

class UserRoleEnum
{
    protected static array $values = [
        'seller',
        'buyer',
    ];

    public static function values(): array
    {
        return self::$values;
    }
}