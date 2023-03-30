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
                'name' => 'User 1',
                'username' => 'user1',
                'is_admin' => true,
                'password' => 'secret'
            ],
            [
                'name' => 'User 2',
                'username' => 'user2',
                'is_admin' => true,
                'password' => 'secret'
            ],
            [
                'name' => 'User 3',
                'username' => 'user3',
                'password' => 'secret'
            ],
            [
                'name' => 'User 4',
                'username' => 'user4',
                'password' => 'secret'
            ],
        ];

        foreach ($users as $userData) {
            User::create(array_merge(Arr::except($userData, 'role'), ['password' => $userData['password']]));
        }
    }
}
