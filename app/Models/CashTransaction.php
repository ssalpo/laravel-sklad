<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

/**
 * @property Order $order
 * @property NomenclatureOperation $nomenclatureOperation
 * @method static completed()
 * @method static canceled()
 * @method static cancel()
 * @method static haveNotEditableRelations()
 */
class CashTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'status',
        'amount',
        'amount_in_dollar',
        'dollar_exchange_rate',
        'comment',
        'created_by',
        'order_id',
        'order_id',
        'nomenclature_operation_id',
    ];

    protected $casts = [
        'amount' => 'double',
        'amount_in_dollar' => 'double',
        'dollar_exchange_rate' => 'double',
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

    public static function boot()
    {
        parent::boot();

        self::creating(static function ($m) {
            $m->created_by = $m->created_by ?? auth()->id();
        });
    }

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

    public function scopeHaveNotEditableRelations($q): void
    {
        $q->whereNull('order_id')
            ->whereNull('nomenclature_operation_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function nomenclatureOperation()
    {
        return $this->belongsTo(NomenclatureOperation::class);
    }

    public function cancel()
    {
        return self::findOrFail($this->id)->update(['status' => self::STATUS_CANCELED]);
    }
}
