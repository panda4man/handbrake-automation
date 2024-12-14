<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/api/compression/update', [App\Http\Controllers\CompressionController::class, 'update'])->name('compression.update');

require __DIR__.'/auth.php';
