<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class BuildCompressScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:compress-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build out the compress script filling in placeholder variables.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $content = file_get_contents(config('handbrake.script_stub'));

        $content = str_replace(
            '{{PATH}}',
            Storage::disk(config('handbrake.io.logs.disk'))->path(config('handbrake.io.logs.folder')),
            $content
        );

        $process = Process::run('which HandBrakeCLI');
        $handbrakeCliPath = trim($process->output());
        $content = str_replace('{{HANDBRAKECLI}}', $handbrakeCliPath, $content);

        file_put_contents(config('handbrake.script'), $content);

        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}
