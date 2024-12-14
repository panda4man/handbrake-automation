<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->makePendingFolders();
        $this->makeOutputFolder();
    }

    private function makePendingFolders(): void
    {
        $presets = array_keys(config('handbrake.presets'));
        $disk = config('handbrake.io.input.disk');
        $base_folder = config('handbrake.io.input.folder');

        foreach ($presets as $preset) {
            $folder_path = $base_folder.'/'.$preset;
            if (! Storage::disk($disk)->exists($folder_path)) {
                Storage::disk($disk)->makeDirectory($folder_path);
            }
        }
    }

    private function makeOutputFolder(): void
    {
        $disk = config('handbrake.io.output.disk');
        $folder = config('handbrake.io.output.folder');

        if (! Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder);
        }
    }
}
