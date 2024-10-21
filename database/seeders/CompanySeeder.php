<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'company_name' => 'Djasoft',
            'ruc' => '10703150506',
            'address' => 'Sullana, Ignacio Escudero',
            'email' => 'contact@djasoft.com',
            'phone' => '948860381',
            'status' => 'active'
        ]);
    }
}