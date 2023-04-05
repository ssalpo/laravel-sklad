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
        'quantity',
        'price',
        'price_for_sale',
    ];

    protected $casts = [
        'price' => 'double',
        'price_for_sale' => 'double',
    ];

    protected $appends = ['can_edit'];

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

    public function getCanEditAttribute()
    {
        return now()->diffInDays($this->created_at) <= 0;
    }
}
