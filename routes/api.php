<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemsController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\PeopleController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ModulesController;
use App\Http\Controllers\Api\ReceiptsController;
use App\Http\Controllers\Api\RoleModuleController;
use App\Http\Controllers\Api\UnitsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserModuleController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Api\UnitConversionsController;
use App\Http\Controllers\Api\MovementsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se definen todas las rutas de la API RESTful para el backend de gestión de inventario.
| Las rutas están organizadas por funcionalidad para facilitar el mantenimiento.
|
*/

// ============================================================================
// CORS y Utilidades Generales
// ============================================================================

// Maneja TODAS las peticiones OPTIONS (preflight) para CORS
Route::options('{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

// Ruta de prueba para verificar que CORS funciona correctamente
Route::get('/cors-test', function () {
    return response()->json([
        'message' => 'CORS funcionando correctamente',
        'timestamp' => now(),
    ]);
});

// ============================================================================
// Autenticación y Gestión de Usuarios
// ============================================================================

// Login de usuarios
Route::post('/login', [AuthController::class, 'login']);

// Obtener información del usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Gestión de contraseñas
Route::post('/password/update', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/password/change', [AuthController::class, 'changePassword']);
Route::post('/password/reset', [EmailController::class, 'sendPasswordReset']);

// ============================================================================
// Gestión de Usuarios, Roles y Módulos
// ============================================================================

// CRUD completo para usuarios
Route::apiResource('users', UsersController::class);

// CRUD completo para roles
Route::apiResource('roles', RolesController::class);

// CRUD completo para módulos del sistema
Route::apiResource('modules', ModulesController::class);

// CRUD completo para relaciones rol-módulo
Route::apiResource('role_modules', RoleModuleController::class);

// Obtener módulos asignados a un usuario por ID
Route::get('/get-modules/{id}', [UserModuleController::class, 'getModules']);

// ============================================================================
// Gestión de Personas
// ============================================================================

// CRUD completo para personas (proveedores/clientes)
Route::apiResource('people', PeopleController::class);

// ============================================================================
// Gestión de Categorías
// ============================================================================

// CRUD completo para categorías de productos
Route::apiResource('categories', CategoriesController::class);

// ============================================================================
// Gestión de Unidades y Conversiones
// ============================================================================

// CRUD completo para unidades de medida
Route::apiResource('units', UnitsController::class);

// CRUD completo para conversiones de unidades
Route::apiResource('unit-conversions', UnitConversionsController::class);

// Obtener unidades disponibles para una unidad base
Route::get('/get-units/{base_unit}', [UnitConversionsController::class, 'getUnits']);

// ============================================================================
// Gestión de Ítems y Productos
// ============================================================================

// CRUD completo para ítems/productos
Route::apiResource('items', ItemsController::class);

// Rutas adicionales para ítems con información de stock
Route::get('/items/with-stock', [App\Http\Controllers\Api\ItemController2::class, 'indexWithStock']);
Route::get('/items/with-stock/{id}', [App\Http\Controllers\Api\ItemController2::class, 'showWithStock']);

// ============================================================================
// Gestión de Inventario
// ============================================================================

// CRUD completo para inventario
Route::apiResource('inventory', InventoryController::class);

// ============================================================================
// Gestión de Movimientos (Kardex)
// ============================================================================

// CRUD completo para movimientos de inventario
Route::apiResource('/movements', MovementsController::class);

// ============================================================================
// Gestión de Recibos
// ============================================================================

// CRUD completo para recibos de compra
Route::apiResource('receipts', ReceiptsController::class);

// ============================================================================
// Dashboard y Estadísticas
// ============================================================================

// Estadísticas generales del dashboard
Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'getStats']);

// Estadísticas para gráficos de movimientos
Route::get('/dashboard/chart-stats', [App\Http\Controllers\Api\DashboardController::class, 'getMovementsStats']);

// Estadísticas circulares (por categorías, etc.)
Route::get('/dashboard/circle-stats', [App\Http\Controllers\Api\DashboardController::class, 'getCircleStats']);

// Últimos movimientos realizados
Route::get('/dashboard/last-movements', [App\Http\Controllers\Api\DashboardController::class, 'getLastMovements']);

// Ítems con más movimientos
Route::get('/dashboard/items-most-movements', [App\Http\Controllers\Api\DashboardController::class, 'getItemsWithMostMovements']);

// Flujo de movimientos
Route::get('/dashboard/movement-flow', [App\Http\Controllers\Api\DashboardController::class, 'getMovementFlow']);

// ============================================================================
// Exportaciones y Reportes
// ============================================================================

// Exportar inventario completo a Excel
Route::get('/export-inventory', [InventoryController::class, 'exportInventory']);

// Exportar kardex de un ítem en un rango de fechas
Route::get('/export-kardex/{item_id}/{init_date}/{end_date}', [InventoryController::class, 'exportKardex']);

// ============================================================================
// Checkout y Pagos en Línea
// ============================================================================

// Procesar pago en línea inteligente
Route::post('/checkout', [App\Http\Controllers\Api\CheckoutController::class, 'store']);

// Obtener información de compra mediante hash del delivery
Route::get('/checkout/hash/{hash}', [App\Http\Controllers\Api\CheckoutController::class, 'showByHash']);

// ============================================================================
// Soporte y Correos Electrónicos
// ============================================================================

// Enviar mensaje de soporte
Route::post('/support/send', [EmailController::class, 'sendSupport']);
