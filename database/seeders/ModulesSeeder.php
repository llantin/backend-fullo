<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        Module::create([
            'name' => 'Personas',
            'route' => '/personas',
            'icon' => 'LuHandshake'
        ]);
        Module::create([
            'name' => 'Usuarios',
            'route' => '/usuarios',
            'icon' => 'TbUsers'
        ]);
        Module::create([
            'name' => 'Roles',
            'route' => '/roles',
            'icon' => 'LuShield'
        ]);
        Module::create([
            'name' => 'Categorias',
            'route' => '/categorias',
            'icon' => 'TbList'
        ]);
        Module::create([
            'name' => 'Artículos',
            'route' => '/articulos',
            'icon' => 'TbHammer'
        ]);
        Module::create([
            'name' => 'Comprobantes',
            'route' => '/comprobantes',
            'icon' => 'TbReceipt'
        ]);
        Module::create([
            'name' => 'Movimientos',
            'route' => '/movimientos',
            'icon' => 'TbArrowsDiff'
        ]);
        Module::create([
            'name' => 'Unidades de medida',
            'route' => '/unidades-medida',
            'icon' => 'TbRulerMeasure'
        ]);
        Module::create([
            'name' => 'Conversión de unidades',
            'route' => '/conversion-unidades',
            'icon' => 'TbChartFunnel'
        ]);
        Module::create([
            'name' => 'Inventario',
            'route' => '/inventario',
            'icon' => 'TbPackage'
        ]);
    }
}
