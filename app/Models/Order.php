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

    public function scopeFilter($q, $data)
    {
        $q->when(
            Arr::get($data, 'client_id'),
            fn($q, $v) => $q->where('client_id', $v)
        )
            ->when(
                Arr::get($data, 'user_id'),
                fn($q, $v) => $q->where('user_id', $v)
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
}
