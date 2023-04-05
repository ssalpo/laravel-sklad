<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone'
    ];

    public function discounts()
    {
        return $this->hasMany(ClientDiscount::class);
    }

    public function debts()
    {
        return $this->hasMany(ClientDebt::class);
    }
}
