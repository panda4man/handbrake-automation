<?php

namespace App\HandBrake\Data;

class AudioTrack
{
    public function __construct(
        public string $audio_encoding,
        public string $audio_language,
        public int $track_number,
        public string $audio_channel,
        public string $raw_description
    )
    {}
}
