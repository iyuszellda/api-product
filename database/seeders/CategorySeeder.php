<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Clothes',
                'slug' => 'clothes',
                'image' => 'https://i.imgur.com/QkIa5tT.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'image' => 'https://i.imgur.com/ZANVnHE.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Furniture',
                'slug' => 'furniture',
                'image' => 'https://i.imgur.com/Qphac99.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Shoes',
                'slug' => 'shoes',
                'image' => 'https://i.imgur.com/qNOjJje.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Miscellaneous',
                'slug' => 'miscellaneous',
                'image' => 'https://i.imgur.com/BG8J0Fj.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}