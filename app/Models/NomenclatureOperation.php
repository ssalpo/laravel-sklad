<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

/**
 * @property CashTransaction $cashTransaction
 */
class NomenclatureOperation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomenclature_id',
        'type',
        'quantity',
        'price',
        'price_for_sale',
        'order_id',
        'order_item_id',
        'comment'
    ];

    protected $casts = [
        'type' => 'int',
        'price' => 'double',
        'price_for_sale' => 'double',
    ];

    public const OPERATION_TYPE_WITHDRAW = 1;
    public const OPERATION_TYPE_INVENTORY = 2;
    public const OPERATION_TYPE_REFUND = 3;

    public const OPERATION_LABELS = [
        self::OPERATION_TYPE_WITHDRAW => 'Списание',
        self::OPERATION_TYPE_REFUND => 'Возврат',
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function cashTransaction()
    {
        return $this->hasOne(CashTransaction::class);
    }

    public function scopeTypeWithdraw($q)
    {
        return $q->whereType(self::OPERATION_TYPE_WITHDRAW);
    }

    public function scopeTypeRefund($q)
    {
        return $q->whereType(self::OPERATION_TYPE_REFUND);
    }

    public function getCanEditAttribute()
    {
        return now()->diffInDays($this->created_at) <= 0;
    }

    public function scopeFilter($q, array $data = [])
    {
        $q->when(Arr::get($data, 'order'), fn($q, $v) => $q->whereOrderId($v));

        $q->when(Arr::get($data, 'nomenclature'), fn($q, $v) => $q->whereNomenclatureId($v));
    }
}
