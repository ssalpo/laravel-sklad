<?php

namespace Database\Seeders;

use App\Models\Nomenclature;
use App\UnitConvertor;
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
                'price_for_sale' => 300,
                'currency_type' => 1,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'Товар 2',
                'type' => 1,
                'price_for_sale' => 500,
                'currency_type' => 1,
                'unit' => UnitConvertor::UNIT_PCS,
            ],
            [
                'name' => 'Сырье 1',
                'type' => 2,
                'price_for_sale' => 2000,
                'currency_type' => 2,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'Сырье 2',
                'type' => 2,
                'price_for_sale' => 1560,
                'currency_type' => 2,
                'unit' => UnitConvertor::UNIT_T,
            ],

        ];

        foreach ($nomenclatures as $nomenclature) {
            Nomenclature::create($nomenclature);
        }
    }
}
