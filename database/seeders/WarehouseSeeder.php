<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Warehouse::create([
            'name' => 'Almacén Principal',
            'location' => 'Sullana, Ignacio Escudero',
            'phone' => '948860381',
            'company_id' => 1
        ]);
    }
}