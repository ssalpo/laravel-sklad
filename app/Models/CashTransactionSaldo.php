<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransactionSaldo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'period',
        'balance'
    ];

    protected $casts = [
        'period' => 'date',
        'balance' => 'float'
    ];
}
