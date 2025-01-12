<?php

namespace App\HandBrake\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CompressionInfo extends Data
{
    public function __construct(
        public string|Optional $status,
        public int|Optional    $pid,
        public string|Optional $elapsed_time,
        public string|Optional $cpu_usage,
        public string|Optional $memory_usage,
        public string|Optional $progress,
        public string|Optional $eta,
        public string|Optional $message,
    )
    {
    }
}
