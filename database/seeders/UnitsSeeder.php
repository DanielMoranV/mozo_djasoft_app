<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'Kilogramo', 'symbol' => 'kg'],
            ['name' => 'Gramo', 'symbol' => 'g'],
            ['name' => 'Litro', 'symbol' => 'l'],
            ['name' => 'Mililitro', 'symbol' => 'ml'],
            ['name' => 'Metro', 'symbol' => 'm'],
            ['name' => 'Centímetro', 'symbol' => 'cm'],
            ['name' => 'Unidad', 'symbol' => 'u'],
            ['name' => 'Caja', 'symbol' => 'caja'],
            ['name' => 'Paquete', 'symbol' => 'paquete'],
            ['name' => 'Botella', 'symbol' => 'botella'],
            ['name' => 'Lata', 'symbol' => 'lata'],
            ['name' => 'Bolsa', 'symbol' => 'bolsa'],
            ['name' => 'Sobre', 'symbol' => 'sobre'],
            ['name' => 'Tubo', 'symbol' => 'tubo'],
            ['name' => 'Tarro', 'symbol' => 'tarro'],
            ['name' => 'Frasco', 'symbol' => 'frasco'],
            ['name' => 'Barra', 'symbol' => 'barra']
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}