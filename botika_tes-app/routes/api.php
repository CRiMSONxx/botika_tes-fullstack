<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KaryawanController;
use App\Http\Controllers\API\DivisiController;
use App\Http\Controllers\API\PekerjaanController;
use App\Http\Controllers\API\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route untuk login
Route::post('/login', [AuthController::class, 'login']);

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'index'])->name('api.count_karyawan'); // Get all pekerjaan
    Route::post('/', [KaryawanController::class, 'store']); // Create a new pekerjaan
    Route::put('{id}', [KaryawanController::class, 'update']); // Update a pekerjaan by id
    Route::delete('{id}', [KaryawanController::class, 'destroy']); // Delete a pekerjaan by id
});

Route::middleware('auth:sanctum')->prefix('pekerjaan')->group(function () {
    Route::get('/', [PekerjaanController::class, 'index'])->name('api.list_pekerjaan'); // Get all pekerjaan
    Route::post('/', [PekerjaanController::class, 'store']); // Create a new pekerjaan
    Route::put('{id}', [PekerjaanController::class, 'update']); // Update a pekerjaan by id
    Route::delete('{id}', [PekerjaanController::class, 'destroy']); // Delete a pekerjaan by id
});

Route::middleware('auth:sanctum')->prefix('divisi')->group(function () {
    Route::get('/', [DivisiController::class, 'index'])->name('api.list_divisi'); // Get all pekerjaan
    Route::post('/', [DivisiController::class, 'store']); // Create a new pekerjaan
    Route::put('{id}', [DivisiController::class, 'update']); // Update a pekerjaan by id
    Route::delete('{id}', [DivisiController::class, 'destroy']); // Delete a pekerjaan by id
});



Route::middleware('auth:sanctum')->get('karyawans', [KaryawanController::class, 'semua']); // Get all pekerjaan