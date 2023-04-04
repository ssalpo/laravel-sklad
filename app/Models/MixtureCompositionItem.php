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
        'end_result',
    ];

    protected $casts = [
        'end_result' => 'boolean',
        'quantity' => 'double'
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
