<?php

namespace App\Enums;

final class WarehouseMovementDirection
{
    public const IN = 'in';
    public const OUT = 'out';

    public static function values(): array
    {
        return [self::IN, self::OUT];
    }

    public static function labels(): array
    {
        return [
            self::IN => 'Приход',
            self::OUT => 'Расход',
        ];
    }

    public static function label(string $direction): string
    {
        return self::labels()[$direction] ?? $direction;
    }
}
