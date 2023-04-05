<?php

namespace Database\Seeders;

use App\Models\Nomenclature;
use App\Services\UnitConvertor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NomenclaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nomenclatures = [
            [
                'name' => 'Жидкое мыло 5л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'Family Lux 500г.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_G,
            ],
            [
                'name' => 'Ойнашуяк 500г.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_G,
            ],
            [
                'name' => 'May 1л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'Family 900г.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_G,
            ],
            [
                'name' => 'Family 450г.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_G,
            ],
            [
                'name' => 'Famely Lux 5л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'Famely гель 5л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'May 3л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'Famely 5л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'Универсал 5л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'Famely 2.5л.',
                'type' => 1,
                'price' => 0,
                'markup' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_L,
            ],
            [
                'name' => 'АОС',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'трифолиф',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'неоноль',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'слесь',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'соль',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'краска',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'абеска',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'щелочь',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'парметол',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'отдушка',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'акузол',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_G,
            ],
            [
                'name' => 'аромат',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'туз',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'бетаин',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'этикетка',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'флакон',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'коробка',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'крышка',
                'type' => 2,
                'price' => 0,
                'price_for_sale' => 0,
                'unit' => UnitConvertor::UNIT_PCS,
            ],

        ];

        foreach ($nomenclatures as $nomenclature) {
            Nomenclature::create($nomenclature);
        }
    }
}
