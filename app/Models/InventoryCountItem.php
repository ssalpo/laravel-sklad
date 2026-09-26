<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryCountItem extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_count_id', 'nomenclature_id', 'book_quantity', 'actual_quantity'];

    protected $casts = ['book_quantity' => 'decimal:6', 'actual_quantity' => 'decimal:6'];

    public function count(): BelongsTo
    {
        return $this->belongsTo(InventoryCount::class, 'inventory_count_id');
    }

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
