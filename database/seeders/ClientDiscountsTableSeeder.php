<?php

namespace Database\Seeders;

use App\Models\ClientDiscount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientDiscountsTableSeeder extends Seeder
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
                'client_id' => 1,
                'nomenclature_id' => 1,
                'discount' => 1
            ],
            [
                'client_id' => 1,
                'nomenclature_id' => 2,
                'discount' => 0.50
            ],
            [
                'client_id' => 2,
                'nomenclature_id' => 1,
                'discount' => 1.20
            ],
            [
                'client_id' => 2,
                'nomenclature_id' => 2,
                'discount' => 0.30
            ],
        ];

        foreach ($data as $datum) {
            ClientDiscount::create($datum);
        }
    }
}
