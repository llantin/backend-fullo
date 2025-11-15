<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web para la aplicación. Estas rutas son
| cargadas por el RouteServiceProvider dentro de un grupo que contiene
| el middleware "web". Ahora crea algo genial!
|
*/

// Ruta principal que muestra la vista de bienvenida
Route::get('/', function () {
    return view('welcome');
});
