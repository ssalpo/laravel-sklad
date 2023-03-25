<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MixtureCompositionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'mixture_composition_id',
        'nomenclature_id',
        'price',
        'quantity',
        'unit',
    ];

    public function mixtureComposition()
    {
        return $this->belongsTo(MixtureComposition::class);
    }

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
