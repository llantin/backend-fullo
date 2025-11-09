<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Module;
use App\Models\RoleModule;

class RoleModulesSeeder extends Seeder
{
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
