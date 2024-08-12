<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

/**
 * @property CashTransaction $cashTransaction
 */
class ClientDebt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'order_id',
        'amount',
        'comment',
        'created_by',
        'is_paid',
        'client_debt_id'
    ];

    protected $casts = [
        'is_paid' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(static function (ClientDebt $m) {
            $m->payments()->delete();
            $m->cashTransaction()->delete();
        });

        self::creating(static function ($m) {
            $m->created_by = $m->created_by ?? auth()->id();
        });
    }

    public function scopeFilter($q, array $filterData = [])
    {
        $q->when(Arr::get($filterData, 'client'), fn($q, $v) => $q->whereClientId($v));

        $q->when(Arr::get($filterData, 'order'), fn($q, $v) => $q->whereOrderId($v));

        $q->when(
            Arr::get($filterData, 'payment_date'),
            function($q, $v) {
                $dateFrom = Carbon::parse($v[0])->startOfDay();
                $dateTo = Carbon::parse($v[1])->endOfDay();

                $q->whereHas('payments', fn($q) => $q->whereBetween('created_at', [$dateFrom, $dateTo]));
            }
        );
    }

    public function scopeRelatedToMe($q)
    {
        return $q->whereHas('order', fn($q) => $q->my());
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function cashTransaction(): HasOne
    {
        return $this->hasOne(CashTransaction::class);
    }

    public function payments()
    {
        return $this->hasMany(ClientDebtPayment::class);
    }

    public function lastPayment(): HasOne
    {
        return $this->hasOne(ClientDebtPayment::class)->latestOfMany('created_at');
    }

    public function markAsPaid()
    {
        return $this->update(['is_paid' => true]);
    }
}
