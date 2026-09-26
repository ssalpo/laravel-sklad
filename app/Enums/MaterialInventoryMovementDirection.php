<?php

namespace App\Enums;

final class MaterialInventoryMovementDirection
{
    public const IN = 'in';

    public const OUT = 'out';

    public static function labels(): array
    {
        return [self::IN => 'Приход', self::OUT => 'Расход'];
    }
}
