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
        $this->call(RolesSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(StorehousesTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
    }
}
