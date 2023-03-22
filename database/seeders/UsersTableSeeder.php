<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = app()->isLocal()
            ? [
                [
                    'name' => 'User 1',
                    'username' => 'user1',
                    'password' => 'secret',
                    'role' => 'admin'
                ],
                [
                    'name' => 'User 2',
                    'username' => 'user2',
                    'password' => 'secret',
                    'role' => 'resident'
                ],
                [
                    'name' => 'User 3',
                    'username' => 'user3',
                    'password' => 'secret',
                    'role' => 'doctor'
                ],
                [
                    'name' => 'User 4',
                    'username' => 'user4',
                    'password' => 'secret',
                    'role' => 'doctor'
                ],
            ]
            : [
                [
                    'name' => 'Султонов Рустам Алпомишевич',
                    'username' => 'rustam',
                    'password' => 'secret',
                    'role' => 'admin'
                ]
            ];

        foreach ($users as $userData) {
            $user = User::create(array_merge(Arr::except($userData, 'role'), ['password' => $userData['password']]));

            $user->assignRole($userData['role']);
        }
    }
}
