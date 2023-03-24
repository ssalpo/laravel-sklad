<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomenclatureArrival extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomenclature_id',
        'quantity',
        'unit_id',
        'price',
        'price_for_sale',
        'currency_type',
        'comment',
        'arrival_at'
    ];

    protected $casts = [
        'price' => 'double',
        'price_for_sale' => 'double',
        'arrival_at' => 'datetime'
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
