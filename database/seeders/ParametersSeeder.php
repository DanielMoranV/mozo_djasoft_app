<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Parameter::create([
            'warehouse_id' => 1,
            'sunat_send' => false,
            'locked' => false,
            'user_id' => 1,
            'company_id' => 1,
        ]);
    }
}
