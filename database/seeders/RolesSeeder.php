<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User; // Asegúrate de importar el modelo User

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear roles
        $devRole = Role::create(['name' => 'dev']);
        Role::create(['name' => 'admin']);
        //Asignar el rol "dev" al usuario con ID 1
        $user = User::find(1);
        if ($user) {
            $user->assignRole($devRole);
        }
    }
}
