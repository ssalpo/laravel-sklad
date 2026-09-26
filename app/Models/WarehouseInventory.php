<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WarehouseInventory extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_POSTED = 'posted';

    protected $fillable = ['status', 'comment', 'created_by', 'posted_at'];

    protected $casts = ['posted_at' => 'datetime'];

    public static function statusLabels(): array
    {
        return array_map(static fn (array $details): string => $details['label'], self::statusDetails());
    }

    public static function statusDetails(): array
    {
        return [
            self::STATUS_DRAFT => ['label' => 'Черновик — остатки не меняются', 'description' => 'Документ можно редактировать: добавлять товары и указывать фактическое количество.', 'example' => 'Пересчёт начали, но его результаты ещё не отражены в остатках.'],
            self::STATUS_POSTED => ['label' => 'Проведена — остатки обновлены', 'description' => 'Разница между учётным и фактическим количеством отражена в журнале движений.', 'example' => 'Недостача создаёт расход, а излишек — приход.'],
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(WarehouseInventoryItem::class);
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(WarehouseMovement::class, 'source');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
