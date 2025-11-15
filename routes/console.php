<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Aquí puedes definir todos tus comandos de consola basados en Closure
| en lugar de separarlos en clases individuales de comandos. Cada Closure
| está vinculado a una instancia de comando, permitiendo un enfoque simple
| para definir el comportamiento de los comandos de consola.
|
*/

// Comando de ejemplo que muestra una cita inspiradora
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
