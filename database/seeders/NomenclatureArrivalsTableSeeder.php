<?php

namespace Database\Seeders;

use App\Models\NomenclatureArrival;
use App\Services\UnitConvertor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NomenclatureArrivalsTableSeeder extends Seeder
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
                'quantity' => 12,
                'unit' => UnitConvertor::UNIT_PCS,
                'price_for_sale' => 13,
                'comment' => 'Simple arrival comment',
                'arrival_at' => now()
            ],
            [
                'nomenclature_id' => 2,
                'quantity' => 15,
                'unit' => UnitConvertor::UNIT_PCS,
                'price_for_sale' => 15,
                'comment' => 'Simple arrival comment 2',
                'arrival_at' => now()
            ],
        ];

        foreach ($data as $item) {
            NomenclatureArrival::create($item);
        }
    }
}
