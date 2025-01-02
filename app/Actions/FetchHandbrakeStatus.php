<?php

namespace App\Actions;

use App\Models\FileCompression;
use Illuminate\Support\Facades\Process;

class FetchHandbrakeStatus
{
    /**
     * Fetch the status of the HandBrake CLI process for a given FileCompression model.
     */
    public function handle(FileCompression $file_compression): array
    {
        if (! $file_compression->pid) {
            return [
                'status' => 'error',
                'message' => 'No PID available for this file compression job.',
            ];
        }

        $status_data = [
            'status' => 'not_running',
            'pid' => $file_compression->pid,
            'elapsed_time' => null,
            'cpu_usage' => null,
            'memory_usage' => null,
            'progress' => null,
            'eta' => null,
        ];

        // Check if the process is running using `ps`
        $process = Process::run("ps -p {$file_compression->pid} -o pid,etime,%cpu,%mem");

        if ($process->successful() && str_contains($process->output(), (string) $file_compression->pid)) {
            $output = $process->output();
            $lines = explode("\n", trim($output));

            if (count($lines) > 1) {
                // Extract the process details (excluding the header line)
                $details = preg_split('/\s+/', $lines[1]);

                $status_data['status'] = 'running';
                $status_data['elapsed_time'] = $details[1];
                $status_data['cpu_usage'] = $details[2].'%';
                $status_data['memory_usage'] = $details[3].'%';
            }
        }

        // Check the log file for progress and ETA

        if (file_exists($file_compression->log_file)) {
            // Use the `tail` command to get the last part of the file
            $output = shell_exec("tail -n 1 " . escapeshellarg($file_compression->log_file));

            // Split the output by `\r` to get individual lines
            $lines = explode("\r", $output);

            // Get the last non-empty line
            $last_line = '';
            for ($i = count($lines) - 1; $i >= 0; $i--) {
                if (trim($lines[$i]) !== '') {
                    $last_line = $lines[$i];
                    break;
                }
            }

            // Extract progress and ETA using regex
            if (preg_match('/Encoding:.*?([0-9]+\.[0-9]+) %.*ETA ([0-9hms]+)/', $last_line, $matches)) {
                $status_data['progress'] = $matches[1] . '%';
                $status_data['eta'] = $matches[2];
            }
        }

        return $status_data;
    }
}
