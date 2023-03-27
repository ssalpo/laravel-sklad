<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'nomenclature_id',
        'price',
        'price_for_sale',
        'quantity',
        'unit',
        'discount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class);
    }
}
