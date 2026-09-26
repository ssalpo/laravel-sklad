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
        return array_column(self::details(), 'label', 'value');
    }

    /**
     * Human-readable business descriptions for the finished-goods warehouse journal.
     */
    public static function details(): array
    {
        return [
            ['value' => self::PURCHASE, 'label' => 'Поступление товара', 'direction' => 'Увеличивает остаток', 'description' => 'Фиксирует приём товара на склад от поставщика.', 'example' => 'Приняли 20 упаковок товара от поставщика.'],
            ['value' => self::PRODUCTION, 'label' => 'Поступление из производства', 'direction' => 'Увеличивает остаток', 'description' => 'Отражает передачу готовой продукции из производства на склад.', 'example' => 'На склад передали 100 готовых изделий.'],
            ['value' => self::PRODUCTION_CONSUMPTION, 'label' => 'Передача в производство', 'direction' => 'Уменьшает остаток', 'description' => 'Отражает передачу товара или компонента со склада в производство.', 'example' => 'Со склада выдали 5 упаковок для производственного участка.'],
            ['value' => self::SALE, 'label' => 'Отгрузка покупателю', 'direction' => 'Уменьшает остаток', 'description' => 'Создаётся при отгрузке товара по заказу покупателя.', 'example' => 'По заказу №125 отгружены 3 единицы товара.'],
            ['value' => self::CUSTOMER_RETURN, 'label' => 'Возврат от покупателя', 'direction' => 'Увеличивает остаток', 'description' => 'Фиксирует возврат ранее отгруженного товара на склад.', 'example' => 'Покупатель вернул 1 единицу товара.'],
            ['value' => self::SUPPLIER_RETURN, 'label' => 'Возврат поставщику', 'direction' => 'Уменьшает остаток', 'description' => 'Фиксирует передачу товара обратно поставщику.', 'example' => 'Поставщику вернули 2 единицы товара с браком.'],
            ['value' => self::WRITE_OFF, 'label' => 'Списание товара', 'direction' => 'Уменьшает остаток', 'description' => 'Используется для брака, порчи, утилизации или другого выбытия.', 'example' => 'Списали повреждённую при хранении упаковку.'],
            ['value' => self::INVENTORY_IN, 'label' => 'Излишек по инвентаризации', 'direction' => 'Увеличивает остаток', 'description' => 'Создаётся, когда фактическое количество больше учётного.', 'example' => 'В учёте 8 шт., фактически 10 шт.: добавляются 2 шт.'],
            ['value' => self::INVENTORY_OUT, 'label' => 'Недостача по инвентаризации', 'direction' => 'Уменьшает остаток', 'description' => 'Создаётся, когда фактическое количество меньше учётного.', 'example' => 'В учёте 10 шт., фактически 8 шт.: списываются 2 шт.'],
            ['value' => self::REVERSAL, 'label' => 'Отмена проведённого движения', 'direction' => 'Меняет остаток в обратную сторону', 'description' => 'Не удаляет исходную запись, а создаёт обратное движение для сохранения истории.', 'example' => 'Отмена ошибочной отгрузки возвращает товар на склад.'],
            ['value' => self::ADJUSTMENT_IN, 'label' => 'Корректировка остатка: увеличение', 'direction' => 'Увеличивает остаток', 'description' => 'Ручное исправление остатка, не связанное с поступлением или инвентаризацией.', 'example' => 'Добавили 3 шт., пропущенные в старом документе.'],
            ['value' => self::ADJUSTMENT_OUT, 'label' => 'Корректировка остатка: уменьшение', 'direction' => 'Уменьшает остаток', 'description' => 'Ручное исправление остатка, не связанное со списанием или инвентаризацией.', 'example' => 'Убрали 3 шт., ошибочно добавленные ранее.'],
        ];
    }

    public static function label(string $type): string
    {
        return self::labels()[$type] ?? $type;
    }
}
