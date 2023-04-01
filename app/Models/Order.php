<?php

namespace App\Models;

use App\Models\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, CurrentUser;

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

    public function scopeStatusNew($q)
    {
        $q->whereStatus(self::STATUS_NEW);
    }

    public function scopeStatusSend($q)
    {
        $q->whereStatus(self::STATUS_SEND);
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
