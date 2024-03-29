<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(NomenclaturesTableSeeder::class);
        $this->call(NomenclatureArrivalsTableSeeder::class);
        $this->call(MixtureCompositionsSeeder::class);
        $this->call(ClientDiscountsTableSeeder::class);
    }
}
