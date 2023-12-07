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
        'client_id',
        'nomenclature_id',
        'quantity',
        'price',
        'total_amount',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RawMaterialPayment::class);
    }

    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function scopeFilter($q, array $data)
    {
        $q->when(isset($data['date'][0], $data['date'][1]), function ($q) use ($data) {
            $q->whereDate('created_at', '>=', $data['date'][0])
                ->whereDate('created_at', '<=', $data['date'][1]);
        });

        $q->when(\Arr::get($data, 'client'), fn($q, $v) => $q->where('client_id', $v));
    }
}
