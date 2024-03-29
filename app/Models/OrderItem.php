<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

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
