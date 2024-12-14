<?php

return [
    'io' => [
        'input' => [
            'disk' => 'local',
            'folder' => 'pending',
        ],
        'output' => [
            'disk' => 'local',
            'folder' => 'completed',
        ],
        'logs' => [
            'disk' => 'local',
            'folder' => 'compression-logs',
        ],
    ],
    'script' => base_path('compress_file.sh'),
    'script_stub' => base_path('compress_file.sh.stub'),
    'queue' => 'default',
    'watch' => 60, // seconds

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
    ],
];
