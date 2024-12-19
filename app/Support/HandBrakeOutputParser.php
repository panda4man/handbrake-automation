<?php

namespace App\Support;

use App\HandBrake\Data\AudioTrack;
use App\HandBrake\Data\SubtitleTrack;
use App\Models\FileCompression;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class HandBrakeOutputParser
{
    public static function parseTracks(FileCompression $file_compression): array
    {
        // Run the HandBrakeCLI --scan command on the input file
        $input_file = $file_compression->input_file;
        $command = sprintf('%s --scan -i %s 2>&1', escapeshellcmd(config('handbrake.cli')), escapeshellarg($input_file));
        $process = Process::run($command);

        if (!$process->successful()) {
            throw new \RuntimeException('Failed to run HandBrakeCLI --scan command.');
        }

        $output = $process->output();
        Log::debug('HandBrakeCLI --scan output: ' . $output);

        $lines = explode("\n", $output);
        $tracks = [
            'audio_tracks' => [],
            'subtitle_tracks' => [],
        ];

        $current_section = null;

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect sections
            if (str_starts_with($line, '+ audio tracks:')) {
                $current_section = 'audio_tracks';
                continue;
            } elseif (str_starts_with($line, '+ subtitle tracks:')) {
                $current_section = 'subtitle_tracks';
                continue;
            } elseif (str_starts_with($line, '+ chapters:')) {
                $current_section = null; // Ignore chapters
                continue;
            }

            // Parse audio tracks
            if ($current_section === 'audio_tracks' && preg_match('/^\+ (\d+), (.+?) \((.+?), (.+?) ch(?:, \d+ kbps)?\) \(iso639-2: (.+?)\)$/', $line, $matches)) {
                $audio_encoding = $matches[3];

                if (!isset(config('handbrake.audio.encode_mappings')[$audio_encoding])) {
                    throw new \RuntimeException("Missing encoding mapping for: $audio_encoding");
                }

                $tracks['audio_tracks'][] = new AudioTrack(
                    audio_encoding: config('handbrake.audio.encode_mappings')[$audio_encoding],
                    audio_language: $matches[5],
                    track_number: (int) $matches[1],
                    audio_channel: $matches[4],
                    raw_description: $line
                );
            }

            // Parse subtitle tracks
            if ($current_section === 'subtitle_tracks' && preg_match('/^\+ (\d+), (.+)$/', $line, $matches)) {
                $tracks['subtitle_tracks'][] = new SubtitleTrack(
                    track_number: (int) $matches[1],
                    language: $matches[2],
                    raw_description: $line
                );
            }
        }

        return $tracks;
    }
}
