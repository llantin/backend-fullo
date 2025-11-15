<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

/**
 * Seeder para poblar la tabla de roles con el rol base.
 *
 * Crea el rol principal de administrador que tiene acceso
 * completo a todos los módulos del sistema.
 */
class RoleSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de roles.
     *
     * Crea el rol 'Administrador del Sistema' con permisos completos.
     * Este rol es asignado al usuario principal del sistema.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Administrador del Sistema',
            'description' => 'Acceso a todos los modulos del sistema.',
        ]);
    }
}
