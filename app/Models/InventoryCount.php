<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryCount extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_COMPLETED = 'completed';

    public static function statusDetails(): array
    {
        return [
            self::STATUS_DRAFT => ['label' => 'Черновик', 'description' => 'Инвентаризация ещё редактируется и не меняет остатки.', 'example' => 'Можно добавлять материалы и указывать фактическое количество.'],
            self::STATUS_COMPLETED => ['label' => 'Проведена', 'description' => 'Разница между учётным и фактическим количеством отражена в остатках.', 'example' => 'Недостача создаёт расход, излишек — приход.'],
        ];
    }

    public static function statusLabels(): array
    {
        return array_map(static fn (array $details): string => $details['label'], self::statusDetails());
    }

    protected $fillable = ['status', 'comment', 'started_at', 'completed_at', 'created_by', 'completed_by'];

    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime'];

    public function items(): HasMany
    {
        return $this->hasMany(InventoryCountItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
