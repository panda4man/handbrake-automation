<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FileCompression;
use App\Actions\CompressFile;

class WatchPendingFileCompressions extends Command
{
    protected $signature = 'watch:pending-compression';
    protected $description = 'Watch for pending file compressions and start a new one if none are active.';

    /**
     * @throws \Exception
     */
    public function handle(): int
    {
        // Check if there is an active compression
        $active_compression = FileCompression::where('active', true)->first();

        if ($active_compression) {
            $this->info('A compression is already active.');
            return 0;
        }

        // Find the next pending file
        $pending_compression = FileCompression::pending()->first();

        if (!$pending_compression) {
            $this->info('No pending compressions found.');
            return 0;
        }

        // Mark the compression as active
        $pending_compression->compress();

        $this->info('Started compression for: ' . $pending_compression->file_name);

        return 0;
    }
}
