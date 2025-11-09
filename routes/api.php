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
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ItemController2;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Devuelve los items junto con su stock actual
Route::get('/items/with-stock', [App\Http\Controllers\Api\ItemController2::class, 'indexWithStock']);
Route::get('/items/with-stock/{id}', [App\Http\Controllers\Api\ItemController2::class, 'showWithStock']);


Route::apiResource('items', ItemsController::class);
Route::apiResource('categories', CategoriesController::class);
Route::apiResource('roles', RolesController::class);
Route::apiResource('people', PeopleController::class);
Route::apiResource('users', UsersController::class);
Route::apiResource('modules', ModulesController::class);
Route::apiResource('role_modules', RoleModuleController::class);
Route::apiResource('units', UnitsController::class);
Route::apiResource('unit-conversions', UnitConversionsController::class);
Route::apiResource('receipts', ReceiptsController::class);
Route::apiResource('inventory', InventoryController::class);
Route::apiResource('/movements', MovementsController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/update', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/password/change', [AuthController::class, 'changePassword']);
Route::get('/get-modules/{id}', [UserModuleController::class, 'getModules']);
Route::get('/get-units/{base_unit}', [UnitConversionsController::class, 'getUnits']);
Route::post('/support/send', [EmailController::class, 'sendSupport']);
Route::post('/password/reset', [EmailController::class, 'sendPasswordReset']);

Route::get('/export-inventory', [InventoryController::class, 'exportInventory']);
Route::get('/export-kardex/{item_id}/{init_date}/{end_date}', [InventoryController::class, 'exportKardex']);

Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'getStats']);
Route::get('/dashboard/chart-stats', [App\Http\Controllers\Api\DashboardController::class, 'getMovementsStats']);
Route::get('/dashboard/circle-stats', [App\Http\Controllers\Api\DashboardController::class, 'getCircleStats']);
Route::get('/dashboard/last-movements', [App\Http\Controllers\Api\DashboardController::class, 'getLastMovements']);
Route::get('/dashboard/items-most-movements', [App\Http\Controllers\Api\DashboardController::class, 'getItemsWithMostMovements']);
Route::get('/dashboard/movement-flow', [App\Http\Controllers\Api\DashboardController::class, 'getMovementFlow']);

// Pagos en línea  inteligente
Route::post('/checkout', [App\Http\Controllers\Api\CheckoutController::class, 'store']);
//Mediante el codigo del delivery se puede obtener toda la informacion despues de la compra
Route::get('/checkout/hash/{hash}', [App\Http\Controllers\Api\CheckoutController::class, 'showByHash']);

// Maneja TODAS las peticiones OPTIONS (preflight)
Route::options('{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');

// Ruta de prueba CORS
Route::get('/cors-test', function () {
    return response()->json([
        'message' => 'CORS funcionando correctamente',
        'timestamp' => now(),
    ]);
});
