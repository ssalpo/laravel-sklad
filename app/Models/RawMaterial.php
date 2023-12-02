<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['payments'];

    protected $casts = [
        'deleted_at' => 'datetime'
    ];

    protected $fillable = [
        'nomenclature_id',
        'quantity',
        'price',
        'total_amount',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(RawMaterialPayment::class);
    }

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::get(
            fn() => sprintf('Номенклатуры [%s] от [%s] на сумму [$%s]', $this->nomenclature->name, $this->created_at->format('d.m.Y'), $this->total_amount)
        );
    }
}
