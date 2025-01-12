<?php

Route::post('/compression/update', [App\Http\Controllers\CompressionController::class, 'update'])
    ->name('compression.update');

Route::get('/compressions/current', \App\Http\Controllers\Api\CurrentCompressionController::class)
    ->name('compressions.current');
