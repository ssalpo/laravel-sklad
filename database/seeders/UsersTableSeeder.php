<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'is_admin' => true,
                'password' => 'oripov9494'
            ]
        ];

        foreach ($users as $userData) {
            User::create(array_merge(Arr::except($userData, 'role'), ['password' => $userData['password']]));
        }
    }
}
