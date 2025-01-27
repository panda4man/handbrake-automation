<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/compressions/current', \App\Http\Controllers\WebApi\CurrentCompressionController::class)
     ->name('compressions.current');

require __DIR__.'/auth.php';
