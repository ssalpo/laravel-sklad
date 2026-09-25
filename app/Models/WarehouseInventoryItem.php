<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseInventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_inventory_id', 'nomenclature_id', 'book_quantity', 'actual_quantity',
    ];

    protected $casts = [
        'book_quantity' => 'decimal:6',
        'actual_quantity' => 'decimal:6',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(WarehouseInventory::class, 'warehouse_inventory_id');
    }

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
