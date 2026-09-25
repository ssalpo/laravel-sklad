<?php

namespace App\Enums;

final class WarehouseMovementType
{
    public const PURCHASE = 'purchase';
    public const PRODUCTION = 'production';
    public const PRODUCTION_CONSUMPTION = 'production_consumption';
    public const SALE = 'sale';
    public const CUSTOMER_RETURN = 'customer_return';
    public const SUPPLIER_RETURN = 'supplier_return';
    public const WRITE_OFF = 'write_off';
    public const INVENTORY_IN = 'inventory_in';
    public const INVENTORY_OUT = 'inventory_out';
    public const REVERSAL = 'reversal';
    public const ADJUSTMENT_IN = 'adjustment_in';
    public const ADJUSTMENT_OUT = 'adjustment_out';

    public static function values(): array
    {
        return [
            self::PURCHASE, self::PRODUCTION, self::PRODUCTION_CONSUMPTION,
            self::SALE, self::CUSTOMER_RETURN, self::SUPPLIER_RETURN,
            self::WRITE_OFF, self::INVENTORY_IN, self::INVENTORY_OUT,
            self::REVERSAL, self::ADJUSTMENT_IN, self::ADJUSTMENT_OUT,
        ];
    }

    public static function labels(): array
    {
        return [
            self::PURCHASE => 'Закупка / приход',
            self::PRODUCTION => 'Производство',
            self::PRODUCTION_CONSUMPTION => 'Расход в производстве',
            self::SALE => 'Продажа',
            self::CUSTOMER_RETURN => 'Возврат от покупателя',
            self::SUPPLIER_RETURN => 'Возврат поставщику',
            self::WRITE_OFF => 'Списание',
            self::INVENTORY_IN => 'Инвентаризация: излишек',
            self::INVENTORY_OUT => 'Инвентаризация: недостача',
            self::REVERSAL => 'Сторно',
            self::ADJUSTMENT_IN => 'Корректировка прихода',
            self::ADJUSTMENT_OUT => 'Корректировка расхода',
        ];
    }

    public static function label(string $type): string
    {
        return self::labels()[$type] ?? $type;
    }
}
