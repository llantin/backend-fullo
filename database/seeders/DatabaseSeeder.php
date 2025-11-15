<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal de la base de datos.
 *
 * Este seeder coordina la ejecución de todos los seeders individuales
 * en el orden correcto para mantener la integridad referencial.
 * Se ejecuta con el comando: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Ejecutar todos los seeders de la base de datos.
     *
     * El orden de ejecución es crítico para mantener las dependencias:
     * 1. PeopleSeeder - Crea personas base
     * 2. RoleSeeder - Crea roles del sistema
     * 3. ModulesSeeder - Crea módulos del sistema
     * 4. RoleModulesSeeder - Asocia roles con módulos
     * 5. UserSeeder - Crea usuarios (depende de personas y roles)
     * 6. MoreCategories - Crea categorías de productos
     * 7. UnitSeeder - Crea unidades de medida
     * 8. UnitConversionSeeder - Crea conversiones entre unidades
     * 9. MoreItems - Crea productos (depende de categorías)
     * 10. MoreReceipts - Crea comprobantes y movimientos (depende de todo lo anterior)
     */
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
