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
                'name' => 'Товар 1',
                'type' => 1,
                'price' => 10,
                'price_for_sale' => 13,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'Товар 2',
                'type' => 1,
                'price' => 10,
                'price_for_sale' => 14,
                'unit' => UnitConvertor::UNIT_PCS,
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
                'name' => 'неональ',
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

        ];

        foreach ($nomenclatures as $nomenclature) {
            Nomenclature::create($nomenclature);
        }
    }
}
