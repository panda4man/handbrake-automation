<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class HandBrakeFolders
{
    public static function pendingFolders(): array
    {
        $base = Storage::disk(config('handbrake.io.input.disk'))->path(config('handbrake.io.input.folder'));

        return collect(array_keys(config('handbrake.presets')))->map(function ($type) use ($base) {
            return "$base/$type";
        })->all();
    }
}
