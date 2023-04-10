<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'status',
        'amount',
        'comment',
        'created_by',
        'order_id',
        'order_id',
        'nomenclature_operation_id',
    ];

    public const TYPE_DEBIT = 1;
    public const TYPE_CREDIT = 2;

    public const TYPE_LABELS = [
        self::TYPE_DEBIT => 'Приход',
        self::TYPE_CREDIT => 'Уход',
    ];

    public const STATUS_COMPLETED = 1;
    public const STATUS_CANCELED = 2;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function nomenclatureOperation()
    {
        return $this->belongsTo(NomenclatureOperation::class);
    }
}
