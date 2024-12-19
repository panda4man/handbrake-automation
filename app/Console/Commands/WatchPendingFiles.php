<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\FileCompression;

class WatchPendingFiles extends Command
{
    protected $signature = 'watch:pending-files';
    protected $description = 'Watch the pending folders for new files.';

    public function handle(): int
    {
        $this->processPendingFiles();

        return 0;
    }

    protected function processPendingFiles(): void
    {
        $input_disk = config('handbrake.io.input.disk');
        $input_folder = config('handbrake.io.input.folder');

        // Get all preset folders in the pending input folder
        $preset_folders = Storage::disk($input_disk)->directories($input_folder);

        foreach ($preset_folders as $preset_folder) {
            // Recursively fetch all files in the current preset folder
            $files = Storage::disk($input_disk)->allFiles($preset_folder);

            foreach ($files as $file) {
                $this->processFile($file, $input_disk, $input_folder);
            }
        }
    }

    protected function processFile(string $file, string $disk, string $base_folder): void
    {
        $file_name = basename($file);
        $relative_path = str_replace("$base_folder/", '', $file);

        // Check if the file has an allowed extension
        $allowed_extensions = config('handbrake.file_types', []);
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (! in_array(".$extension", $allowed_extensions)) {
            $this->warn("Skipped file with unsupported extension: $relative_path");
            return;
        }

        // Skip if the file already exists in the database
        if (FileCompression::where('relative_path', $relative_path)->exists()) {
            return;
        }

        // Extract file type from the folder name
        $folder_name = explode('/', $relative_path)[0] ?? null;
        $file_type = $folder_name && array_key_exists($folder_name, config('handbrake.presets'))
            ? $folder_name
            : null;

        if (! $file_type) {
            $this->warn("Skipped file with unknown file type: $relative_path");
            return;
        }

        // Create a new FileCompression entry
        FileCompression::create([
            'file_name' => $file_name,
            'file_size_before' => Storage::disk($disk)->size($file),
            'file_type' => $file_type,
            'relative_path' => $relative_path,
        ]);

        $this->info("> New file found: $relative_path");
    }
}
