<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Person;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'ccardenas',
            'password' => bcrypt('admin'),
            'role_id' => Role::where('name', 'Administrador del Sistema')->first()->id,
            'person_id' => Person::where('email', '1512755@senati.pe')->first()->id,
        ]);
        // 👇 NUEVO usuario “sistema_ventas”
        $person = Person::create([
            'name' => 'Sistema',
            'last_name' => 'Ventas',
            'email' => 'sistema@ventas.com',
            'phone' => '000000000',
            'type' => 'Sistema',
            'identification_type' => 'RUC',
            'identification_number' => '00000000000',
        ]);

        User::create([
            'person_id' => $person->id,
            'username' => 'sistema_ventas',
            'password' => bcrypt('123456'),
            'role_id' => 1, // o el rol que prefieras (admin, ventas, etc.)
        ]);
    }
}
