<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'category_name' => 'Tanpa Kategori',
            'description' => '',
        ]);

        Category::create([
            'category_name' => 'Hijab',
            'description' => '',
        ]);

        Category::create([
            'category_name' => 'Mukena',
            'description' => '',
        ]);

        Category::create([
            'category_name' => 'Aksesoris',
            'description' => '',
        ]);
    }
}
