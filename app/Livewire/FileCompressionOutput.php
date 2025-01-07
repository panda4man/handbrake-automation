<?php

namespace App\Livewire;

use Livewire\Component;

class FileCompressionOutput extends Component
{
    public ?int $total_space_saved = null;
    public ?int $total_compression_time = null;

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function refreshStats(): void
    {
        $this->total_space_saved = 0;
        $this->total_compression_time = 0;

        foreach (\App\Models\FileCompression::completed()->get() as $compression) {
            // Calculate space saved (original size - compressed size)
            $space_saved = $compression->file_size_before - $compression->file_size_after;
            $this->total_space_saved += max($space_saved, 0); // Ensure no negative values

            // Calculate total compression time (seconds)
            $compression_time = $compression->completed_at->timestamp - $compression->started_at->timestamp;
            $this->total_compression_time += max($compression_time, 0); // Ensure no negative values
        }

        $this->total_space_saved = round($this->total_space_saved / (1024 ** 3), 2); // Convert bytes to GB and round to 2 decimals
    }

    public function render()
    {
        return view('livewire.file-compression-output');
    }
}
