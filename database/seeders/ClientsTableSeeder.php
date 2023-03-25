<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
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
                'name' => 'Клиент 1',
                'phone' => '79521621026',
                'discount' => 10
            ],
            [
                'name' => 'Клиент 2',
                'phone' => '992929927233',
                'discount' => 5
            ],
            [
                'name' => 'Клиент 3',
                'phone' => '992926002080',
                'discount' => 8
            ],
        ];

        foreach ($data as $item) {
            Client::create($item);
        }
    }
}
