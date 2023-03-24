<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'type',
        'price_for_sale',
        'currency_type',
        'unit_id',
    ];

    protected $casts = [
        'price' => 'double'
    ];

    public const TYPE_SALE = 1; // продажа
    public const TYPE_COMPOSITE = 2; // составной

    public const TYPES_LIST = [
        self::TYPE_SALE => 'Продажа',
        self::TYPE_COMPOSITE => 'Составной',
    ];

    public const CURRENCY_TYPE_TJS = 1;
    public const CURRENCY_TYPE_USD = 2;

    public const CURRENCY_TYPES = [
        self::CURRENCY_TYPE_TJS => 'Сомони',
        self::CURRENCY_TYPE_USD => 'Доллары',
    ];

    public const CURRENCY_TYPES_SHORT = [
        self::CURRENCY_TYPE_TJS => 'сом.',
        self::CURRENCY_TYPE_USD => '$',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
