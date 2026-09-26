<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRun extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public static function statusDetails(): array
    {
        return [
            self::STATUS_DRAFT => ['label' => 'Черновик', 'description' => 'Выпуск подготовлен, но материалы ещё не списаны.', 'example' => 'Можно изменить количество, дату или комментарий до проведения.'],
            self::STATUS_COMPLETED => ['label' => 'Проведён', 'description' => 'Выпуск подтверждён, материалы списаны по закреплённой версии рецептуры.', 'example' => 'Для выпуска 100 изделий создан расход каждого материала из рецепта.'],
            self::STATUS_CANCELLED => ['label' => 'Отменён', 'description' => 'Проведённый выпуск отменён: для списаний созданы обратные приходные движения.', 'example' => 'Материалы возвращены на склад, исходная история сохранена.'],
        ];
    }

    public static function statusLabels(): array
    {
        return array_map(static fn (array $details): string => $details['label'], self::statusDetails());
    }

    protected $fillable = ['nomenclature_id', 'recipe_id', 'quantity', 'status', 'produced_at', 'comment', 'created_by'];

    protected $casts = ['quantity' => 'decimal:6', 'produced_at' => 'datetime'];

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(ProductionRecipe::class, 'recipe_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
