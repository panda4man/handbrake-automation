<?php

namespace App\Console\Commands;

class WatchPendingFiles extends Command
{
    protected $signature = 'file-watcher:run';
    protected $description = 'Watch the pending folders for new files.';

    public function handle()
    {
        $pendingFolders = [
            storage_path('pending/standard'),
            storage_path('pending/bluray'),
            storage_path('pending/bluray_animated'),
        ];

        foreach ($pendingFolders as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
        }

        // Example watcher loop
        while (true) {
            foreach ($pendingFolders as $folder) {
                $subfolders = glob("$folder/*", GLOB_ONLYDIR);

                if (empty($subfolders)) {
                    $subfolders = [$folder]; // Default to the main folder if no subfolders exist
                }

                foreach ($subfolders as $subfolder) {
                    $files = glob("$subfolder/*.mkv");
                    $jobs = [];

                    foreach ($files as $file) {
                        $fileName = basename($file);

                        // Skip if the file already exists in the database
                        if (FileCompression::where('file_name', $fileName)->exists()) {
                            continue;
                        }

                        $fileType = basename($folder);

                        $compression = FileCompression::create([
                            'file_name' => $fileName,
                            'file_size_before' => filesize($file),
                            'file_type' => $fileType,
                        ]);

                        $jobs[] = new CompressFileJob($compression);
                    }

                    if (!empty($jobs)) {
                        dispatchBatch($jobs)->name(basename($subfolder));
                    }
                }
            }

            sleep(30); // Check every 30 seconds
        }
    }
}