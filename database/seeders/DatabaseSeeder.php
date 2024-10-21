<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CompanySeeder::class,
            UnitsSeeder::class,
            ProviderSeeder::class,
            CategoriesSeeder::class,
            CategoryMovementsSeeder::class

        ]);

        $company = Company::where('company_name', 'Djasoft')->first();

        if ($company) {
            User::factory()->create([
                'name' => 'Daniel Moran Vilchez',
                'email' => 'daniel.moranv94@gmail.com',
                'password' => bcrypt('admin3264'),
                'dni' => '70315050',
                'phone' => '948860381',
                'company_id' => $company->id
            ]);
        }

        $this->call([
            RolesSeeder::class,
            ProductsSeeder::class,
            WarehouseSeeder::class,
            ParametersSeeder::class,
            PurchaseOrderSeeder::class
        ]);
    }
}