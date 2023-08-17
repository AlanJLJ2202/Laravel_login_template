<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    /**CATEGORIES */

    Route::resource('categories', CategoryController::class);

    // Ruta para obtener transacciones
    Route::get('/transactions', [TransactionController::class, 'get_transactions']);

    // Ruta para registrar una transacción
    Route::post('/transactions', [TransactionController::class, 'register_transaction']);
});
