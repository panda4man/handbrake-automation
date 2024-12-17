<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileCompression extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected function logFile(): Attribute
    {
        return Attribute::get(function () {
            return Storage::disk(config('handbrake.io.logs.disk'))->path(
                sprintf('%s/compression_%d.log', config('handbrake.io.logs.folder'), $this->id)
            );
        });
    }
}
