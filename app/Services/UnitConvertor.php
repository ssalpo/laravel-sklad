<?php

namespace App\Services;

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

    public const MASS_UNITS = [
        self::UNIT_T,
        self::UNIT_KG,
        self::UNIT_G,
        self::UNIT_MG
    ];

    public const FORMULAS = [
        self::UNIT_T => 1000,
        self::UNIT_KG => 1,
        self::UNIT_G => 0.001,
        self::UNIT_MG => 0.000001
    ];

    public static function toGram(int $value, int $currentUnit)
    {
        if (in_array($currentUnit, self::MASS_UNITS)) {

            if ($currentUnit === self::UNIT_G) {
                return $value;
            }

            $base = $value * self::FORMULAS[$currentUnit];

            return $base / self::FORMULAS[self::UNIT_G];
        }

        return 0;
    }

    public static function toKg(float $value, int $currentUnit, int $precision = 3)
    {
        if ($currentUnit === self::UNIT_L) {
            return $value;
        }

        if (in_array($currentUnit, self::MASS_UNITS)) {
            $base = $value * self::FORMULAS[$currentUnit];

            return round($base / self::FORMULAS[self::UNIT_KG], $precision, PHP_ROUND_HALF_UP);
        }

        return 0;
    }
}
