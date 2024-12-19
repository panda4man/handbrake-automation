<?php

namespace App\HandBrake\Data;

class SubtitleTrack
{
    public function __construct(
        public int $track_number,
        public string $language,
        public string $raw_description,
    )
    {}
}
