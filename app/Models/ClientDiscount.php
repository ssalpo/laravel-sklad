<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDiscount extends Model
{
    use HasFactory;

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
