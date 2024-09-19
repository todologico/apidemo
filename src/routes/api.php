<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PharmacyController;

//------------------------------------------------------

// Auth registro
Route::post('/register', [AuthController::class, 'create'])->name('register');

// Auth login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Auth eliminar token
Route::post('/revoke-token', [AuthController::class, 'revokeToken'])->name('revoke')->middleware('auth:sanctum');

//-------------------------------------------------------

//guarda una farmacia
Route::post('/pharmacies-store', [PharmacyController::class, 'storePharmacy'])->name('storepharmacy')->middleware('auth:sanctum');

// obtiene una farmacia por id
Route::get('/pharmacies-show/{id}', [PharmacyController::class, 'showPharmacy'])->name('showpharmacy')->where('id', '[0-9]+')->middleware('auth:sanctum');

// busco una farmacia especifica por latitud y longitud
Route::get('/pharmacy/{lat}/{lon}', [PharmacyController::class, 'findPharmacy'])->name('findpharmacy')->middleware('auth:sanctum');

