<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

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
    ];

    protected $casts = [
        'is_paid' => 'boolean'
    ];

    public function scopeFilter($q, array $filterData = [])
    {
        $q->when(Arr::get($filterData, 'client'), fn($q, $v) => $q->whereClientId($v));

        $q->when(Arr::get($filterData, 'order'), fn($q, $v) => $q->whereOrderId($v));
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

    public function payments()
    {
        return $this->hasMany(ClientDebtPayment::class);
    }

    public function markAsPaid()
    {
        return $this->update(['is_paid' => true]);
    }
}
