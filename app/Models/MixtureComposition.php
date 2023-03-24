<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MixtureComposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomenclature_id',
        'currency_type',
        'weight',
        'weight_unit_id',
        'water',
        'water_unit_id',
        'worker_price'
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function weightUnit()
    {
        return $this->belongsTo(Unit::class, 'weight_unit_id');
    }

    public function waterUnit()
    {
        return $this->belongsTo(Unit::class, 'water_unit_id');
    }
}
