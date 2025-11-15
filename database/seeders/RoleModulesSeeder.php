<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Module;
use App\Models\RoleModule;

/**
 * Seeder para poblar la tabla de relación rol-módulo.
 *
 * Establece permisos completos para todos los roles existentes,
 * asignando acceso a todos los módulos disponibles. Esto crea
 * una matriz de permisos completa para testing inicial.
 */
class RoleModulesSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de permisos rol-módulo.
     *
     * Para cada rol existente, crea una relación con todos los módulos
     * disponibles usando firstOrCreate para evitar duplicados.
     * Esto resulta en permisos completos para todos los roles.
     */
    public function run(): void
    {
        $roles = Role::all();
        $modules = Module::all();

        foreach ($roles as $role) {
            foreach ($modules as $module) {
                RoleModule::firstOrCreate([
                    'role_id' => $role->id,
                    'module_id' => $module->id,
                ]);
            }
        }
    }
}
