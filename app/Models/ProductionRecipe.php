<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionRecipe extends Model
{
    use HasFactory;

    protected $fillable = ['nomenclature_id', 'version', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductionRecipeItem::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(ProductionRun::class, 'recipe_id');
    }
}
