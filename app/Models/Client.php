<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'discount',
        'discount_for_single',
    ];

    protected $casts = [
        'discount_for_single' => 'bool'
    ];
}
