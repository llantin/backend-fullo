<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

/**
 * Seeder para poblar la tabla de módulos del sistema.
 *
 * Crea los módulos principales del sistema de gestión de inventario.
 * Cada módulo representa una funcionalidad o sección del sistema
 * y se utiliza para control de permisos basado en roles.
 */
class ModulesSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de módulos.
     *
     * Crea 10 módulos principales del sistema:
     * - Personas: Gestión de proveedores/clientes
     * - Usuarios: Administración de usuarios del sistema
     * - Roles: Gestión de roles y permisos
     * - Categorias: Clasificación de productos
     * - Artículos: Gestión del catálogo de productos
     * - Comprobantes: Recibos de compra/venta
     * - Movimientos: Kardex e historial de movimientos
     * - Unidades de medida: Sistema de unidades
     * - Conversión de unidades: Factores de conversión
     * - Inventario: Control de stock y existencias
     *
     * Cada módulo incluye nombre, ruta y ícono para la interfaz.
     */
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
