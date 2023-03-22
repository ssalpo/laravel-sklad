<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'type'
    ];

    public const TYPE_SALE = 1; // продажа
    public const TYPE_COMPOSITE = 2; // составной
}
