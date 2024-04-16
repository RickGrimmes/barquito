<?php

use App\Http\Controllers\RegistroController;
use App\Http\Controllers\UserController;
use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// me faltan para mandar el codigo y luego recibirlo, otro para que se pase a activo, y ya creo

// tambien para que vayan creciendo el de victorias y derrotas, y que en registro se cree la partida

Route::post('/reg', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);

    Route::get('/registro', [RegistroController::class, 'index']);
    Route::get('/registro/{id}', [RegistroController::class, 'show']);
});
