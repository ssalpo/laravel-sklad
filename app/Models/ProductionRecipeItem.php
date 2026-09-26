<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRecipeItem extends Model
{
    use HasFactory;

    protected $fillable = ['production_recipe_id', 'material_nomenclature_id', 'quantity_per_unit'];

    protected $casts = ['quantity_per_unit' => 'decimal:6'];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(ProductionRecipe::class, 'production_recipe_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'material_nomenclature_id');
    }
}
