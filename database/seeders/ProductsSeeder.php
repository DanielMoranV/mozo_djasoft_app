<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Unit;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Huevos-6x30', 'category' => 'Alimentos', 'unit' => 'Caja'],
            ['name' => 'Aspiral', 'category' => 'Limpieza', 'unit' => 'Unidad'],
            ['name' => 'Fosforo', 'category' => 'Limpieza', 'unit' => 'Caja'],
            ['name' => 'Jabon Trome', 'category' => 'Limpieza', 'unit' => 'Barra'],
            ['name' => 'Aceite chico', 'category' => 'Alimentos', 'unit' => 'Botella'],
            ['name' => 'Leche chico Azul', 'category' => 'Bebidas', 'unit' => 'Caja'],
            ['name' => 'Leche Bonle Chico', 'category' => 'Bebidas', 'unit' => 'Caja'],
            ['name' => 'Aceite Costa Rey', 'category' => 'Alimentos', 'unit' => 'Botella'],
            ['name' => 'Leche grande azul', 'category' => 'Bebidas', 'unit' => 'Caja'],
            ['name' => 'Doffi', 'category' => 'Higiene', 'unit' => 'Unidad'],
            ['name' => 'Suave', 'category' => 'Higiene', 'unit' => 'Unidad'],
            ['name' => 'Fideo', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Pañales', 'category' => 'Higiene', 'unit' => 'Paquete'],
            ['name' => 'Ajinomoto', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Limpiatodo', 'category' => 'Limpieza', 'unit' => 'Botella'],
            ['name' => 'Sal Marina', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Lejia', 'category' => 'Limpieza', 'unit' => 'Botella'],
            ['name' => 'Avena Osito', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Avena Viejito', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Avena Chocolat', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Sedal', 'category' => 'Higiene', 'unit' => 'Botella'],
            ['name' => 'HYS azul', 'category' => 'Higiene', 'unit' => 'Botella'],
            ['name' => 'Sacheton', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Color Sachet', 'category' => 'Higiene', 'unit' => 'Paquete'],
            ['name' => 'Sibarita', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Cocoa', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Soda', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Fideo Rosca', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Cabello de Angel', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'Dento', 'category' => 'Higiene', 'unit' => 'Tubo'],
            ['name' => 'Altomayo', 'category' => 'Alimentos', 'unit' => 'Paquete'],
            ['name' => 'toallas lady', 'category' => 'Higiene', 'unit' => 'Paquete'],
            ['name' => 'toallas nosotras', 'category' => 'Higiene', 'unit' => 'Paquete'],
            ['name' => 'toallas natura', 'category' => 'Higiene', 'unit' => 'Paquete'],
            ['name' => 'Huevos-3x30', 'category' => 'Alimentos', 'unit' => 'Caja'],
        ];

        foreach ($products as $product) {
            $category = Category::where('name', $product['category'])->first();
            $unit = Unit::where('name', $product['unit'])->first();

            Product::create([
                'name' => $product['name'],
                'category_id' => $category->id,
                'unit_id' => $unit->id,
                'user_id' => 1, // Ajusta este valor según sea necesario
                'code' => random_int(1, 99999), // Genera un código aleatorio, ajusta según sea necesario
            ]);
        }
    }
}