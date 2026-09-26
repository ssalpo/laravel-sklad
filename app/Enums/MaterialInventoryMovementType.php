<?php

namespace App\Enums;

final class MaterialInventoryMovementType
{
    public const RECEIPT = 'receipt';

    public const PRODUCTION_CONSUMPTION = 'production_consumption';

    public const WRITE_OFF = 'write_off';

    public const RETURN_IN = 'return_in';

    public const SUPPLIER_RETURN = 'supplier_return';

    public const INVENTORY_SURPLUS = 'inventory_surplus';

    public const INVENTORY_SHORTAGE = 'inventory_shortage';

    public const ADJUSTMENT_IN = 'adjustment_in';

    public const ADJUSTMENT_OUT = 'adjustment_out';

    public const REVERSAL = 'reversal';

    public static function labels(): array
    {
        return array_column(self::details(), 'label', 'value');
    }

    /**
     * Human-readable business descriptions for the material inventory journal.
     */
    public static function details(): array
    {
        return [
            ['value' => self::RECEIPT, 'label' => 'Поступление материала', 'direction' => 'Увеличивает остаток', 'description' => 'Фиксирует фактическое поступление материала на склад.', 'example' => 'Поставщик привёз 25 кг муки.'],
            ['value' => self::PRODUCTION_CONSUMPTION, 'label' => 'Списание в производство', 'direction' => 'Уменьшает остаток', 'description' => 'Создаётся автоматически при проведении выпуска по рецептуре.', 'example' => 'На выпуск 100 изделий израсходовано 8 кг муки.'],
            ['value' => self::WRITE_OFF, 'label' => 'Списание материала', 'direction' => 'Уменьшает остаток', 'description' => 'Используется для брака, порчи, утилизации или другого выбытия вне производства.', 'example' => '2 кг материала испорчены при хранении.'],
            ['value' => self::RETURN_IN, 'label' => 'Возврат материала на склад', 'direction' => 'Увеличивает остаток', 'description' => 'Возвращает неиспользованный материал, при необходимости с привязкой к выпуску.', 'example' => 'После выпуска осталось 0,5 кг материала и его вернули на склад.'],
            ['value' => self::SUPPLIER_RETURN, 'label' => 'Возврат поставщику', 'direction' => 'Уменьшает остаток', 'description' => 'Фиксирует передачу ранее принятого материала обратно поставщику.', 'example' => 'Поставщику возвращено 10 упаковок с браком.'],
            ['value' => self::INVENTORY_SURPLUS, 'label' => 'Излишек по инвентаризации', 'direction' => 'Увеличивает остаток', 'description' => 'Создаётся при проведении инвентаризации, когда фактическое количество больше учётного.', 'example' => 'В учёте 8 кг, фактически 10 кг: в остаток добавляются 2 кг.'],
            ['value' => self::INVENTORY_SHORTAGE, 'label' => 'Недостача по инвентаризации', 'direction' => 'Уменьшает остаток', 'description' => 'Создаётся при проведении инвентаризации, когда фактическое количество меньше учётного.', 'example' => 'В учёте 10 кг, фактически 8 кг: из остатка списываются 2 кг.'],
            ['value' => self::ADJUSTMENT_IN, 'label' => 'Корректировка остатка: увеличение', 'direction' => 'Увеличивает остаток', 'description' => 'Ручное исправление остатка, когда оно не связано с поступлением или инвентаризацией.', 'example' => 'Исправили ошибочно неучтённые 3 кг в старом документе.'],
            ['value' => self::ADJUSTMENT_OUT, 'label' => 'Корректировка остатка: уменьшение', 'direction' => 'Уменьшает остаток', 'description' => 'Ручное исправление остатка, когда оно не связано со списанием или инвентаризацией.', 'example' => 'Убрали из учёта ошибочно добавленные 3 кг.'],
            ['value' => self::REVERSAL, 'label' => 'Отмена проведённого движения', 'direction' => 'Меняет остаток в обратную сторону', 'description' => 'Не удаляет исходную запись, а создаёт обратное движение для сохранения истории.', 'example' => 'Отмена выпуска возвращает на склад ранее списанные материалы.'],
        ];
    }
}
