<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Товары'
            ],
            [
                'name' => 'Комплектующие'
            ],
            [
                'name' => 'Химия'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
