<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialInventoryMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nomenclature_id', 'type', 'direction', 'quantity', 'source_type', 'source_id', 'parent_id', 'comment', 'occurred_at', 'created_by'];

    protected $casts = ['quantity' => 'decimal:6', 'occurred_at' => 'datetime'];

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function reversals(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
