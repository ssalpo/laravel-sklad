<?php

namespace App\Models;

use App\Models\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Order extends Model
{
    use HasFactory, CurrentUser, SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_id',
        'amount',
        'profit',
        'status',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];

    public const STATUS_NEW = 1;
    public const STATUS_SEND = 2;
    public const STATUS_CANCELED = 3;

    public const STATUS_LABELS = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_SEND => 'Отправлен',
        self::STATUS_CANCELED => 'Отменен',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(static function ($m) {
            $m->orderItems()->delete();
            $m->debts()->delete();
            $m->nomenclatureOperations()->delete();
            $m->cashTransaction()->delete();
        });
    }

    public function scopeFilter($q, $data)
    {
        $q->when(
            Arr::get($data, 'client'),
            fn($q, $v) => $q->where('client_id', $v)
        );

        $q->when(
            Arr::get($data, 'user'),
            fn($q, $v) => $q->where('user_id', $v)
        );

        $q->when(
            Arr::get($data, 'id'),
            fn($q, $v) => $q->where('id', $v)
        );
    }

    public function scopeStatusNew($q)
    {
        $q->whereStatus(self::STATUS_NEW);
    }

    public function scopeStatusSend($q)
    {
        $q->whereStatus(self::STATUS_SEND);
    }

    public function scopeRelatedToMe($q, $check)
    {
        $q->when($check, static fn($q) => $q->my());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function debts()
    {
        return $this->hasMany(ClientDebt::class);
    }

    public function nomenclatureOperations()
    {
        return $this->hasMany(NomenclatureOperation::class);
    }

    public function cashTransaction()
    {
        return $this->hasOne(CashTransaction::class);
    }
}
