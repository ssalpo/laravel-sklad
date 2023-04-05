<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'nomenclature_id',
        'discount'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
