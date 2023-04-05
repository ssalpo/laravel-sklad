<?php

namespace Database\Seeders;

use App\Models\MixtureComposition;
use App\Services\UnitConvertor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

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
                'weight' => 4.8,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 600,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.03,
                'items' => [
                    [
                        'nomenclature_id' => 16,
                        'quantity' => 35,
                        'price' => 70,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 26,
                        'quantity' => 3,
                        'price' => 5.4,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 19,
                        'quantity' => 3,
                        'price' => 6.6,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 23,
                        'quantity' => 5,
                        'price' => 10,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 24,
                        'quantity' => 0.3,
                        'price' => 4.5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 23,
                        'quantity' => 0.5,
                        'price' => 1.5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 25,
                        'quantity' => 35,
                        'price' => 3,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 18,
                        'quantity' => 0.01,
                        'price' => 0.25,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 20,
                        'quantity' => 0.3,
                        'price' => 0.3,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 21,
                        'quantity' => 1,
                        'price' => 2,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 28,
                        'quantity' => 1,
                        'price' => 0.014,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 29,
                        'quantity' => 1,
                        'price' => 0.24,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                ]
            ],
            [
                'nomenclature_id' => 2,
                'weight' => 0.5,
                'weight_unit' => UnitConvertor::UNIT_ML,
                'water' => 600,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.001,
                'items' => [
                    [
                        'nomenclature_id' => 16,
                        'quantity' => 35,
                        'price' => 70,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 26,
                        'quantity' => 3,
                        'price' => 5.4,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 19,
                        'quantity' => 3,
                        'price' => 6.6,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 14,
                        'quantity' => 5,
                        'price' => 10,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 24,
                        'quantity' => 0.3,
                        'price' => 4.5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 23,
                        'quantity' => 0.5,
                        'price' => 1.5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 25,
                        'quantity' => 35,
                        'price' => 3,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 18,
                        'quantity' => 0.01,
                        'price' => 0.25,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 20,
                        'quantity' => 0.3,
                        'price' => 0.3,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 21,
                        'quantity' => 1,
                        'price' => 2,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 27,
                        'quantity' => 1,
                        'price' => 0.011,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 28,
                        'quantity' => 1,
                        'price' => 0.1,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 29,
                        'quantity' => 1,
                        'price' => 0.0021,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                ]
            ],
            [
                'nomenclature_id' => 3,
                'weight' => 0.5,
                'weight_unit' => UnitConvertor::UNIT_ML,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.001,
                'items' => [
                    [
                        'nomenclature_id' => 16,
                        'quantity' => 2,
                        'price' => 4,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 22,
                        'quantity' => 0.3,
                        'price' => 4.5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 18,
                        'quantity' => 0.015,
                        'price' => 1,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 15,
                        'quantity' => 2,
                        'price' => 8,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 28,
                        'quantity' => 1,
                        'price' => 0.078,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 27,
                        'quantity' => 1,
                        'price' => 0.015,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 29,
                        'quantity' => 1,
                        'price' => 0.018,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 30,
                        'quantity' => 1,
                        'price' => 0.115,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                ]
            ],
            [
                'nomenclature_id' => 4,
                'weight' => 1,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 700,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.025,
                'items' => [
                    [
                        'nomenclature_id' => 13,
                        'quantity' => 10,
                        'price' => 70,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 14,
                        'quantity' => 10,
                        'price' => 20,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 15,
                        'quantity' => 5,
                        'price' => 20,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 16,
                        'quantity' => 35,
                        'price' => 70,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 19,
                        'quantity' => 10,
                        'price' => 22,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 20,
                        'quantity' => 1.2,
                        'price' => 1.2,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 21,
                        'quantity' => 1,
                        'price' => 2,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 23,
                        'quantity' => 0.6,
                        'price' => 1.8,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 24,
                        'quantity' => 3,
                        'price' => 81,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 25,
                        'quantity' => 20,
                        'price' => 1,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 26,
                        'quantity' => 5,
                        'price' => 9,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 27,
                        'quantity' => 1,
                        'price' => 0.035,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 28,
                        'quantity' => 1,
                        'price' => 0.37,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 29,
                        'quantity' => 1,
                        'price' => 0.046,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                ]
            ],
            [
                'nomenclature_id' => 5,
                'weight' => 0.9,
                'weight_unit' => UnitConvertor::UNIT_ML,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.001,
                'items' => [
                    [
                        'nomenclature_id' => 16,
                        'quantity' => 60,
                        'price' => 120,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 26,
                        'quantity' => 5,
                        'price' => 9,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 19,
                        'quantity' => 5,
                        'price' => 11,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 21,
                        'quantity' => 1,
                        'price' => 2,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 24,
                        'quantity' => 0.2,
                        'price' => 3,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 25,
                        'quantity' => 50,
                        'price' => 5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 18,
                        'quantity' => 0.05,
                        'price' => 1,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 20,
                        'quantity' => 0.550,
                        'price' => 0.55,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 27,
                        'quantity' => 1,
                        'price' => 0.015,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 28,
                        'quantity' => 1,
                        'price' => 0.116,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 29,
                        'quantity' => 1,
                        'price' => 0.0021,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                ]
            ],
            [
                'nomenclature_id' => 6,
                'weight' => 0.450,
                'weight_unit' => UnitConvertor::UNIT_ML,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.001,
                'items' => [
                    [
                        'nomenclature_id' => 16,
                        'quantity' => 60,
                        'price' => 120,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 26,
                        'quantity' => 5,
                        'price' => 9,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 19,
                        'quantity' => 5,
                        'price' => 11,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 21,
                        'quantity' => 1,
                        'price' => 2,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 24,
                        'quantity' => 0.3,
                        'price' => 3,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 25,
                        'quantity' => 50,
                        'price' => 5,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 18,
                        'quantity' => 0.05,
                        'price' => 1,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 20,
                        'quantity' => 0.550,
                        'price' => 0.55,
                        'unit' => UnitConvertor::UNIT_KG,
                        'end_result' => false,
                    ],
                    [
                        'nomenclature_id' => 27,
                        'quantity' => 1,
                        'price' => 0.011,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 28,
                        'quantity' => 1,
                        'price' => 0.067,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 30,
                        'quantity' => 1,
                        'price' => 0.015,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                    [
                        'nomenclature_id' => 29,
                        'quantity' => 1,
                        'price' => 0.0021,
                        'unit' => UnitConvertor::UNIT_PCS,
                        'end_result' => true,
                    ],
                ]
            ],
            [
                'nomenclature_id' => 7,
                'weight' => 5,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 700,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.03
            ],
            [
                'nomenclature_id' => 8,
                'weight' => 5,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 700,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.035
            ],
            [
                'nomenclature_id' => 9,
                'weight' => 3,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 700,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.025
            ],
            [
                'nomenclature_id' => 10,
                'weight' => 5,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.03
            ],
            [
                'nomenclature_id' => 11,
                'weight' => 5,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.03
            ],
            [
                'nomenclature_id' => 12,
                'weight' => 2.5,
                'weight_unit' => UnitConvertor::UNIT_L,
                'water' => 1000,
                'water_unit' => UnitConvertor::UNIT_L,
                'worker_price' => 0.02
            ],
        ];

        foreach ($data as $datum) {
            $mixture = MixtureComposition::create(Arr::except($datum, 'items'));

            foreach (Arr::get($datum, 'items', []) as $item) {
                $mixture->mixtureCompositionItems()->create($item);
            }
        }
    }
}
