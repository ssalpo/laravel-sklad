<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDebtPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_debt_id',
        'created_by',
        'amount'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public function clientDebt()
    {
        return $this->belongsTo(ClientDebt::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
