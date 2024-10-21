<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Alimentos', 'description' => 'Productos alimenticios'],
            ['name' => 'Bebidas', 'description' => 'Bebidas y refrescos'],
            ['name' => 'Limpieza', 'description' => 'Productos de limpieza'],
            ['name' => 'Higiene', 'description' => 'Productos de higiene personal'],
            // Añade más categorías según sea necesario
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}