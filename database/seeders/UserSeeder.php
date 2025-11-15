<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Person;

/**
 * Seeder para poblar la tabla de usuarios con cuentas base.
 *
 * Crea usuarios iniciales del sistema, incluyendo el administrador
 * principal y un usuario especial para el sistema de ventas.
 */
class UserSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de usuarios.
     *
     * Crea dos usuarios principales:
     * 1. Carlos Cardenas: Administrador principal con credenciales 'ccardenas'/'admin'
     * 2. Sistema Ventas: Usuario especial para operaciones automáticas del sistema
     *
     * Ambos usuarios están vinculados a personas existentes y tienen roles asignados.
     */
    public function run(): void
    {
        User::create([
            'username' => 'ccardenas',
            'password' => bcrypt('admin'),
            'role_id' => Role::where('name', 'Administrador del Sistema')->first()->id,
            'person_id' => Person::where('email', '1512755@senati.pe')->first()->id,
        ]);
        // 👇 NUEVO usuario "sistema_ventas"
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
