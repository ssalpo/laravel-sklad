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
        return [self::STATUS_DRAFT => 'Черновик', self::STATUS_POSTED => 'Проведена'];
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
