<?php

use App\Http\Controllers\API\BarangController;
use App\Http\Controllers\API\PenjualanController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);


/* User */
// Create
Route::post('register', [UserController::class, 'create']);
// Read
Route::post('login', [UserController::class, 'read']);

Route::middleware('auth:sanctum')->group(function () {
    // Update
    Route::post('user_update', [UserController::class, 'update']);
    // Delete
    Route::delete('user_delete', [UserController::class, 'delete']);

    /* Penjualan */
    // Create
    Route::post('penjualan_create', [PenjualanController::class, 'create']);
    // Read
    Route::get('penjualan', [PenjualanController::class, 'read']);
    // Update
    Route::get('penjualan_create', [PenjualanController::class, 'update']);
    // Delete
    Route::delete('penjualan_delete', [PenjualanController::class, 'delete']);
});

/* Barang */
// Create
Route::post('barang_insert', [BarangController::class, 'create']);
// Read
Route::get('barang', [BarangController::class, 'read']);
// Update
Route::post('barang_update', [BarangController::class, 'update']);
// Delete
Route::delete('barang_delete', [BarangController::class, 'delete']);
