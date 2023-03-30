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
                'name' => 'Сырье 1',
                'type' => 2,
                'price' => 10,
                'price_for_sale' => 15,
                'unit' => UnitConvertor::UNIT_KG,
            ],
            [
                'name' => 'Сырье 2',
                'type' => 2,
                'price' => 10,
                'price_for_sale' => 16,
                'unit' => UnitConvertor::UNIT_T,
            ],

        ];

        foreach ($nomenclatures as $nomenclature) {
            Nomenclature::create($nomenclature);
        }
    }
}
