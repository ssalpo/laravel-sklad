<?php

namespace Database\Seeders;

use App\Models\Storehouse;
use Illuminate\Database\Seeder;

class StorehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storehouses = [
            [
                'name' => 'Склад 1'
            ],
            [
                'name' => 'Склад 2'
            ],
            [
                'name' => 'Склад 3'
            ],
            [
                'name' => 'Склад 4'
            ],
        ];

        foreach ($storehouses as $storehouse) {
            Storehouse::create($storehouse);
        }
    }
}
