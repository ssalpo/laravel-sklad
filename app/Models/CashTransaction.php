<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

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

    public const STATUS_LABELS = [
        self::STATUS_COMPLETED => 'Проведен',
        self::STATUS_CANCELED => 'Отменен',
    ];

    public function scopeCompleted($q)
    {
        $q->whereStatus(self::STATUS_COMPLETED);
    }

    public function scopeCanceled($q)
    {
        $q->whereStatus(self::STATUS_CANCELED);
    }

    public function scopeFilter($q, array $data)
    {
        $q->when(Arr::get($data, 'status'), fn($q, $v) => $q->whereStatus($v));

        $q->when(Arr::get($data, 'date'), fn($q, $v) => $q->whereDate('created_at', Carbon::parse($v)));

        $q->when(Arr::get($data, 'type'), fn($q, $v) => $q->where('type', $v));
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function nomenclatureOperation()
    {
        return $this->belongsTo(NomenclatureOperation::class);
    }
}
