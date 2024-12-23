<?php

namespace App\Actions;

use App\Models\FileCompression;
use App\Support\HandBrakeAudio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class CompressFile
{
    /**
     * Execute the file compression process.
     *
     * @param FileCompression $file_compression
     * @return void
     */
    public function handle(FileCompression $file_compression): void
    {
        // Update the file compression record to indicate the process has started
        $file_compression->update([
            'started_at' => now(),
            'failed_at' => null,
            'completed_at' => null,
        ]);

        // Prepare paths and settings
        $input_file = $file_compression->input_file;
        $output_file = $file_compression->output_file;
        $preset_json = config('handbrake.io.presets.use_json') ? $file_compression->preset_file : null;

        // Build the command array
        $command = [
            escapeshellcmd(config('handbrake.script')),
            '-u', escapeshellarg(route('compression.update')),
            '-j', escapeshellarg($file_compression->id),
            '-i', escapeshellarg($input_file),
            '-o', escapeshellarg($output_file),
            '--title', escapeshellarg($file_compression->title)
        ];

        // Add preset args
        if ($preset_json) {
            $command[] = '--preset-import-file';
            $command[] = escapeshellarg($preset_json);
        }

        $command[] = '-Z';
        $command[] = escapeshellarg($file_compression->preset);

        // Append the CLI arguments for audio tracks
        $command = array_merge($command, HandBrakeAudio::buildCLIArgs($file_compression));

        $command[] = '> /dev/null 2>&1 &';

        // make sure that the parent directory for $file_compression->output_file exists
        $output_dir = dirname($file_compression->output_file);

        if (!is_dir($output_dir)) {
            mkdir($output_dir, 0755, true);
        }

        $final_command = implode(' ', $command);

        $file_compression->update(['cli_command' => $final_command]);

        try {
            // Execute the command in the background
            Process::run($final_command);

            Log::info("Compression script sent to background for file: {$file_compression->file_name}");
        } catch (\Throwable $e) {
            // Handle exceptions
            $file_compression->update(['active' => false, 'failed_at' => now()]);
            Log::error("An error occurred while starting compression for file: {$file_compression->file_name}. Error: {$e->getMessage()}");
        }
    }
}
