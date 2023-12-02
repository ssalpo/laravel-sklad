<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterialPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'raw_material_id',
        'amount',
        'comment',
    ];
}
