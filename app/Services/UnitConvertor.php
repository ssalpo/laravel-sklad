<?php

namespace App\Services;

class UnitConvertor
{
    public const UNIT_KG = 2;
    public const UNIT_G = 3;
    public const UNIT_L = 5;
    public const UNIT_ML = 6;
    public const UNIT_PCS = 7;

    public const UNIT_LABELS = [
        self::UNIT_KG => 'кг.',
        self::UNIT_G => 'г.',
        self::UNIT_L => 'л.',
        self::UNIT_ML => 'мл.',
        self::UNIT_PCS => 'шт.',
    ];

    public const MASS_UNITS = [
        self::UNIT_KG,
        self::UNIT_G,
    ];
}
