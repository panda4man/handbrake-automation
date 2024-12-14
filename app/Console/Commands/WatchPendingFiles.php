<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFileJob;
use App\Models\FileCompression;
use App\Support\HandbrakeFolders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class WatchPendingFiles extends Command
{
    protected $signature = 'file-watcher:run';

    protected $description = 'Watch the pending folders for new files.';

    public function handle()
    {
        $pending_folders = HandbrakeFolders::pendingFolders();

        // AppServiceProvider should take care of this...but just in case
        foreach ($pending_folders as $folder) {
            if (! file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
        }

        // Example watcher loop
        while (true) {
            foreach ($pending_folders as $folder) {
                $subfolders = glob("$folder/*", GLOB_ONLYDIR);

                if (empty($subfolders)) {
                    $subfolders = [$folder]; // Default to the main folder if no subfolders exist
                }

                foreach ($subfolders as $subfolder) {
                    $files = glob("$subfolder/*.mkv");
                    $jobs = [];

                    foreach ($files as $file) {
                        $file_name = basename($file);

                        // Skip if the file already exists in the database
                        if (FileCompression::where('file_name', $file_name)->exists()) {
                            continue;
                        }

                        $file_type = basename($folder);

                        $compression = FileCompression::create([
                            'file_name' => $file_name,
                            'file_size_before' => filesize($file),
                            'file_type' => $file_type,
                        ]);

                        $jobs[] = new ProcessFileJob($compression);
                    }

                    if (! empty($jobs)) {
                        $this->info('Dispatching '.count($jobs)." jobs for $subfolder");

                        // Proper batch dispatching using Laravel Bus
                        Bus::batch($jobs)
                            ->name(basename($subfolder))
                            ->onQueue(config('handbrake.queue')) // Specify queue if necessary
                            ->dispatch();
                    }
                }
            }

            sleep(config('handbrake.watch')); // Check every 60 seconds
        }
    }
}
