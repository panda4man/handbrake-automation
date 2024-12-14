<?php

namespace App\Jobs;

use App\Models\FileCompression;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class ProcessFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Batchable;

    protected FileCompression $file_compression;

    /**
     * Create a new job instance.
     */
    public function __construct(FileCompression $file_compression)
    {
        $this->file_compression = $file_compression;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $preset = config('handbrake.presets')[$this->file_compression->file_type] ?? null;

        if (is_null($preset)) {
            $this->fail(new \Exception('No preset found for file type: '.$this->file_compression->file_type));
        }

        // File paths and script parameters
        $input_file = Storage::disk(config('handbrake.io.input.disk'))->path(
            sprintf('%s/%s/%s',
                config('handbrake.io.input.folder'), $this->file_compression->file_type, $this->file_compression->file_name
            )
        );
        $output_file = Storage::disk(config('handbrake.io.output.disk'))->path(
            sprintf('%s/%s', config('handbrake.io.output.folder'), $this->file_compression->file_name)
        );
        $api_url = route('compression.update');
        $job_id = $this->file_compression->id;

        // Determine the preset JSON file path if use_json is true
        $preset_json = null;
        if (config('handbrake.io.presets.use_json')) {
            $preset_json = Storage::disk(config('handbrake.io.presets.disk'))->path(
                sprintf('%s/%s.json', config('handbrake.io.presets.folder'), $preset)
            );
        }

        // Update the job as active
        $this->file_compression->update(['active' => true, 'started_at' => now()]);

        // Build the command to capture PID
        $command = implode(' ', [
            escapeshellcmd(config('handbrake.script')),
            escapeshellarg($input_file),
            escapeshellarg($output_file),
            escapeshellarg($preset),
            escapeshellarg($api_url),
            escapeshellarg($job_id),
            escapeshellarg($preset_json), // Pass the preset JSON file path as the 6th argument
            '>/dev/null 2>&1 & echo $!', // Redirect output and print PID
        ]);

        // Run the command and capture the PID
        $process = Process::run($command);

        if ($process->successful()) {
            $pid = trim($process->output());

            // Update the FileCompression model with the PID
            $this->file_compression->update(['pid' => $pid]);
        } else {
            // Handle failure to start the script
            $this->file_compression->update(['failed_at' => now()]);
        }
    }
}
