<?php

use Illuminate\Support\Facades\Route;
use Botble\RajaOngkir\Http\Controllers\RajaOngkirController;

// Semua endpoint AJAX Raja Ongkir untuk marketplace/Martfury
Route::prefix('ajax/rajaongkir')->group(function () {
    // Mendapatkan daftar provinsi (GET)
    Route::get('provinces', [RajaOngkirController::class, 'getProvinces'])->name('rajaongkir.provinces');
    
    // Mendapatkan daftar kota berdasarkan province_id (GET)
    Route::get('cities', [RajaOngkirController::class, 'getCities'])->name('rajaongkir.cities');
    
    // Menghitung ongkos kirim (POST)
    Route::post('cost', [RajaOngkirController::class, 'getCost'])->name('rajaongkir.cost');
});
