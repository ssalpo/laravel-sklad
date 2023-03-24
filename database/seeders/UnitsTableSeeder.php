<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
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
                'name' => 'шт.'
            ],
            [
                'name' => 'л.'
            ],
            [
                'name' => 'мл.'
            ],
            [
                'name' => 'т.'
            ],
            [
                'name' => 'кг.'
            ],
            [
                'name' => 'гр.'
            ]
        ];

        foreach ($data as $item) {
            Unit::create($item);
        }
    }
}
