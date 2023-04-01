<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'order_id',
        'amount',
        'comment',
        'is_paid',
        'paid_at',
        'created_by'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
