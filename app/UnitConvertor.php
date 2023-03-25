<?php

namespace App;

class UnitConvertor
{
    public const UNIT_T = 1;
    public const UNIT_KG = 2;
    public const UNIT_G = 3;
    public const UNIT_MG = 4;
    public const UNIT_L = 5;
    public const UNIT_ML = 6;
    public const UNIT_PCS = 7;

    public const UNIT_LABELS = [
        self::UNIT_T => 'т.',
        self::UNIT_KG => 'кг.',
        self::UNIT_G => 'г.',
        self::UNIT_MG => 'мг.',
        self::UNIT_L => 'л.',
        self::UNIT_ML => 'мл.',
        self::UNIT_PCS => 'шт.',
    ];
}
