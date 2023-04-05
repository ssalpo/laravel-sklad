<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NomenclatureOperation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomenclature_id',
        'type',
        'quantity'
    ];

    public const OPERATION_TYPE_WITHDRAW = 1;
    public const OPERATION_TYPE_INVENTORY = 2;

    public const OPERATION_LABELS = [
        self::OPERATION_TYPE_WITHDRAW => 'Списание'
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function scopeTypeWithdraw($q)
    {
        return $q->whereType(self::OPERATION_TYPE_WITHDRAW);
    }
}
