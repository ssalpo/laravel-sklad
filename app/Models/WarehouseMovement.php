<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomenclature_id', 'type', 'direction', 'quantity', 'price', 'price_for_sale',
        'source_type', 'source_id', 'order_id', 'order_item_id', 'parent_id', 'comment',
        'occurred_at', 'created_by', 'legacy_source_type', 'legacy_source_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:6',
        'price' => 'decimal:6',
        'price_for_sale' => 'decimal:6',
        'occurred_at' => 'datetime',
    ];

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function reversals(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function cashTransaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CashTransaction::class);
    }
}
