<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PeopleSeeder::class,
            RoleSeeder::class,
            ModulesSeeder::class,
            RoleModulesSeeder::class,
            UserSeeder::class,
            MoreCategories::class,
            UnitSeeder::class,
            UnitConversionSeeder::class,
            MoreItems::class,
            MoreReceipts::class,
        ]);
    }
}
