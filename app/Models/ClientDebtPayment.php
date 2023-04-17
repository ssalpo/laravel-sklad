<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDebtPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_debt_id',
        'created_by',
        'amount'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(static function (ClientDebtPayment $m) {
            $m->cashTransaction()->delete();
        });
    }

    public function clientDebt(): BelongsTo
    {
        return $this->belongsTo(ClientDebt::class);
    }

    public function cashTransaction(): HasOne
    {
        return $this->hasOne(CashTransaction::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
