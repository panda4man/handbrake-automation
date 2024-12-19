<?php

namespace App\Support;

use App\HandBrake\Data\AudioTrack;
use App\HandBrake\Data\SubtitleTrack;
use App\Models\FileCompression;
use Illuminate\Support\Str;

class HandBrakeAudio
{
    /**
     * For the given $file_compress, return the requested audio tracks.
     * The file can request additional languages by appending the iso-639 to the file name.
     *
     * Example: {file_name}--eng-jpn.mkv
     * Example {file_name}--eng.mkv
     *
     * @param FileCompression $file_compress
     * @return array
     */
    public static function getRequestedAudioTracks(FileCompression $file_compress): array
    {
        if (!$file_compress->requests_audio_tracks) {
            return [config('handbrake.audio.track_default')];
        }

        $file_name = pathinfo($file_compress->input_file, PATHINFO_FILENAME);

        preg_match('/--([a-z]{3}(?:-[a-z]{3})*)$/', $file_name, $matches);

        if (isset($matches[1])) {
            return explode('-', $matches[1]);
        }

        return [config('handbrake.audio.track_default')];
    }

    /**
     * Check if this file compression requests audio tracks. By default, we just process {eng} audio tracks.
     *
     * @param FileCompression $file_compression
     * @return bool
     */
    public static function requestsAudioTracks(FileCompression $file_compression): bool
    {
        return Str::contains(
            $file_compression->input_file,
            collect(config('handbrake.audio.tracks'))->map(function ($track) {
                return "-$track";
            })->toArray()
        );
    }

    /**
     * For the language requested, find the highest quality audio track.
     *
     * @param array $audio_tracks
     * @param string $lang
     * @return AudioTrack|null
     */
    protected static function getHighestTrackForLang(array $audio_tracks, string $lang): ?AudioTrack
    {
        $target_encodings = config('handbrake.audio.target_track_encoding');
        $channel_mapping = config('handbrake.audio.channel_mapping');
        $filtered_tracks = collect($audio_tracks)->filter(function (AudioTrack $track) use ($lang) {
            return $track->audio_language === $lang;
        });

        foreach ($target_encodings as $encoding) {
            $highest_tracks = $filtered_tracks->filter(function (AudioTrack $track) use ($encoding) {
                return Str::contains(strtolower($track->audio_encoding), $encoding);
            });

            if ($highest_tracks->isNotEmpty()) {
                foreach ($channel_mapping as $channel => $bitrate) {
                    $highest_track = $highest_tracks->first(function (AudioTrack $track) use ($channel) {
                        return $track->audio_channel === $channel;
                    });

                    if ($highest_track) {
                        return $highest_track;
                    }
                }
            }
        }

        return null;
    }

    /**
     * For the language tracks requested, find the highest quality audio track.
     *
     * @param FileCompression $file_compression
     * @return array
     */
    public static function fetchAudioTracks(FileCompression $file_compression): array
    {
        $tracks = HandBrakeOutputParser::parseTracks($file_compression);
        $requested_tracks = self::getRequestedAudioTracks($file_compression);
        $audio_tracks = [];

        foreach($requested_tracks as $track) {
            $audio_tracks[] = self::getHighestTrackForLang($tracks['audio_tracks'], $track);
        }

        return array_filter($audio_tracks);
    }

    public static function fetchSubtitleTrack(FileCompression $file_compression): ?SubtitleTrack
    {
        $tracks = HandBrakeOutputParser::parseTracks($file_compression);

        return array_filter($tracks['subtitle_tracks'])[0] ?? null;
    }

    public static function buildCLIArgs(FileCompression $file_compression): array
    {
        $audio_tracks = self::fetchAudioTracks($file_compression);
        $subtitle_track = self::fetchSubtitleTrack($file_compression);
        
        $args = [
            '-a' => [],
            '--aencoder' => [],
            '--ab' => [],
            '--mixdown' => [],
            '--arate' => [],
        ];

        foreach ($audio_tracks as $track) {
            // Pass-through audio stream
            $args['-a'][] = $track->track_number;
            $args['--aencoder'][] = 'copy:' . strtolower($track->audio_encoding);
            $args['--ab'][] = '640';
            $args['--mixdown'][] = 'none';
            $args['--arate'][] = 'auto';

            // Stereo mixdown audio stream
            $args['-a'][] = $track->track_number;
            $args['--aencoder'][] = 'av_aac';
            $args['--ab'][] = '256';
            $args['--mixdown'][] = 'stereo';
            $args['--arate'][] = 'auto';
        }

        $cli_args = [];
        foreach ($args as $key => $value) {
            $cli_args[] = $key;
            $cli_args[] = implode(',', $value);
        }

        return $cli_args;
    }
}
