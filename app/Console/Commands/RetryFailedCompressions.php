<?php

namespace App\Console\Commands;

use App\Models\FileCompression;
use Illuminate\Console\Command;

class RetryFailedCompressions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:failed-compressions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry failed compressions.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        FileCompression::query()->failed()->each(function (FileCompression $file_compress) {
            $this->info("Retrying compression for {$file_compress->input_file}");

            $file_compress->retry();
        });

        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}
