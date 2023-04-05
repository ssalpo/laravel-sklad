<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MixtureComposition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomenclature_id',
        'weight',
        'weight_unit',
        'water',
        'water_unit',
        'worker_price'
    ];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function mixtureCompositionItems()
    {
        return $this->hasMany(MixtureCompositionItem::class);
    }
}
