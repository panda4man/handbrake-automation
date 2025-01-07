<?php

namespace App\Livewire;

use App\Actions\FetchHandbrakeStatus;
use App\Models\FileCompression;
use Livewire\Component;

class CurrentCompressionStatus extends Component
{
    public $current_status = [];
    public $active_compression = null;

    public function mount(): void
    {
        $this->fetchCurrentCompression();
    }

    public function fetchCurrentCompression(): void
    {
        $new_active_compression = FileCompression::where('active', 1)->first();

        if ($new_active_compression) {
            $this->active_compression = $new_active_compression;
            $this->updateCompressionStatus();
        } else {
            $this->active_compression = null;
            $this->current_status = [];
        }
    }

    public function updateCompressionStatus(): void
    {
        if ($this->active_compression) {
            $fetchStatus = new FetchHandbrakeStatus;
            $this->current_status = $fetchStatus->handle($this->active_compression);
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('livewire.current-compression-status', [
            'current_status' => $this->current_status,
            'parsed_args' => $this->getCliArgsForDisplay(),
        ]);
    }

    public function getCliArgsForDisplay(): array
    {
        $cli_args = $this->active_compression->cli_args_array ?? [];

        $parsed_args = [];
        $current_flag = null;

        foreach ($cli_args as $arg) {
            if (str_starts_with($arg, '-') || str_starts_with($arg, '--')) {
                // This is a flag, set it as the current key
                $current_flag = $arg;
                $parsed_args[$current_flag] = [];
            } elseif ($current_flag) {
                // This is a value for the last flag
                $parsed_args[$current_flag][] = $arg;
            }
        }

        // Flatten single-item arrays for better readability
        foreach ($parsed_args as $key => $values) {
            if (count($values) === 1) {
                $parsed_args[$key] = $values[0];
            }
        }

        return $parsed_args;
    }

}
