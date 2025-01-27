<?php

Route::post('/compression/update', [App\Http\Controllers\CompressionController::class, 'update'])
    ->name('compression.update');
