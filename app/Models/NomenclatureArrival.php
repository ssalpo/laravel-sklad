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
        'unit',
        'price_for_sale',
        'comment',
        'arrival_at'
    ];

    protected $casts = [
        'price_for_sale' => 'double',
        'arrival_at' => 'datetime'
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
