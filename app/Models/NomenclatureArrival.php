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
        'price',
        'price_for_sale',
        'comment',
        'arrival_at'
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
