<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method priceNotManual
 * @method saleType
 * @method compositeType
 * @method hasPriceForSale
 */
class Nomenclature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'price',
        'price_for_sale',
        'markup',
        'unit',
        'dollar_exchange_rate',
        'is_price_manual'
    ];

    protected $casts = [
        'price' => 'double',
        'price_for_sale' => 'double',
        'markup' => 'double',
        'dollar_exchange_rate' => 'double',
        'is_price_manual' => 'bool'
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

    public function scopeSaleType($q)
    {
        $q->whereType(self::TYPE_SALE);
    }

    public function scopeHasPriceForSale($q)
    {
        $q->where('price', '>', 0);
    }

    public function scopeCompositeType($q)
    {
        $q->whereType(self::TYPE_COMPOSITE);
    }

    public function scopePriceNotManual($q)
    {
        return $q->where('is_price_manual', false);
    }

    public function mixtureComposition()
    {
        return $this->hasOne(MixtureComposition::class);
    }
}
