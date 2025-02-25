<?php

return [
    'io' => [
        'input' => [
            'disk' => env('HANDBRAKE_INPUT_DISK', 'local'),
            'folder' => env('HANDBRAKE_INPUT_FOLDER', 'pending'),
        ],
        'output' => [
            'disk' => env('HANDBRAKE_OUTPUT_DISK', 'local'),
            'folder' => env('HANDBRAKE_OUTPUT_FOLDER', 'completed'),
        ],
        'presets' => [
            'use_json' => env('HANDBRAKE_USE_PRESET_JSON', true),
            'disk' => env('HANDBRAKE_PRESETS_DISK', 'public'),
            'folder' => env('HANDBRAKE_PRESETS_FOLDER', 'presets'),
        ],
        'logs' => [
            'disk' => 'public',
            'folder' => 'compression-logs',
        ],
    ],

    // https://en.wikipedia.org/wiki/List_of_ISO_639_language_codes
    'audio' => [
        'tracks' => ['eng', 'jpn'],
        'track_default' => 'eng',
        // Map the channel to the bitrate
        'channel_mapping' => [
            '7.1' => 640, '7.0' => 640, '6.1' => 640, '5.1' => 640, '5.0' => 640, '2.0' => 256
        ],
        // Map the HandBrakeCLI audio scan encoding to HandBrakeCLI audio encoding options
        'encode_mappings' => [
            'AC3' => 'ac3',
            'DTS-HD MA' => 'dtshd',
            'DTS' => 'dts',
            'E-AC3' => 'eac3',
            'DTS-HD' => 'dtshd',
            'Dolby TrueHD + Dolby Atmos' => 'truehd',
            'DTS-ES' => 'dts',
            'DTS Express' => 'dts',
            'DTS-HD MA + DTS:X' => 'dtshd',
            'DTS-HD HRA' => 'dtshd',
            'AAC LC' => 'av_aac',
            'TrueHD' => 'truehd',
        ],
        // Which encoders have a pass through/copy option
        'allows_pass_through' => ['aac', 'eac3', 'truhd', 'dtshd', 'dts', 'mp2', 'mp3', 'flac', 'opus'],
        // Set the encoding hierarchy. We'll only use one to encode all audio tracks
        'target_track_encoding' => ['truehd', 'dtshd', 'dts', 'eac3', 'ac3', 'aac'],
    ],

    'file_types' => ['.mkv', '.mp4'],

    'cli' => env('HANDBRAKE_CLI', '/usr/local/bin/HandBrakeCLI'),
    'script' => base_path('compress_file.sh'),
    'script_stub' => base_path('compress_file.sh.stub'),

    /* -----------------------------------------------------------
     * These key value pairs map the file type to the preset name.
     * The file type also maps to the folder that type lives in.
     * -----------------------------------------------------------
     */
    'presets' => [
        'bluray-action-1080p' => 'Bluray-Action-1080p',
        'bluray-standard-1080p' => 'Bluray-Standard-1080p',
        'bluray-anime-ghibli-1080p' => 'Bluray-Anime-Ghibli-1080p',
        'bluray-anime-action-1080p' => 'Bluray-Anime-Action-1080p',
        'dvd-standard' => 'DVD-Standard',
        'dvd-animated' => 'DVD-Animated',
        'dvd-action' => 'DVD-Action',
        'dvd-animated-stopmotion' => 'DVD-Animated-Stopmotion',
        'dvd-animated-action' => 'DVD-Animated-Action'
    ],
];
