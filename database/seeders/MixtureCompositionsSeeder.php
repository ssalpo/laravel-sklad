<?php

namespace Database\Seeders;

use App\Models\MixtureComposition;
use App\UnitConvertor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MixtureCompositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nomenclature_id' => 1,
                'weight' => 1,
                'weight_unit' => UnitConvertor::UNIT_KG,
                'water' => 700,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.035
            ],
            [
                'nomenclature_id' => 2,
                'weight' => 900,
                'weight_unit' => UnitConvertor::UNIT_ML,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.035
            ],
        ];

        foreach ($data as $datum) {
            MixtureComposition::create($datum);
        }
    }
}
