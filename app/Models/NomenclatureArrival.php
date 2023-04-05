<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NomenclatureArrival extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomenclature_id',
        'quantity',
        'unit',
        'comment',
        'arrival_at'
    ];

    protected $casts = [
        'arrival_at' => 'datetime'
    ];

    protected $appends = ['can_edit'];

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }

    public function getCanEditAttribute()
    {
        return now()->diffInDays($this->arrival_at) <= 0;
    }
}
