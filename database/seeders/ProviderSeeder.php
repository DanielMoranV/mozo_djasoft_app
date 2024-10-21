<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provider::create([
            'name' => 'Danitec',
            'ruc' => '20703150547',
            'address' => 'Sullana',
            'phone' => '987837278',
            'email' => 'admin@danitec.com'
        ]);
    }
}