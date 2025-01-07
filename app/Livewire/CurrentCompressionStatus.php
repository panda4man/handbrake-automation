<?php

namespace App\Livewire;

use App\Actions\FetchHandbrakeStatus;
use App\Models\FileCompression;
use Livewire\Component;

class CurrentCompressionStatus extends Component
{
    public $current_status = [];
    public $active_compression = null;
    public $parsed_cli_command;

    public function mount(): void
    {
        $this->fetchCurrentCompression();
    }

    public function fetchCurrentCompression(): void
    {
        $new_active_compression = FileCompression::where('active', 1)->first();

        if ($new_active_compression) {
            $this->active_compression = $new_active_compression;
            $this->parsed_cli_command = $this->parseCliCommand($this->active_compression->cli_command);
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
        ]);
    }

    private function parseCliCommand(string $cli_command): array
    {
        $arguments = [];
        $pattern = '/(?:\s|^)(-[a-zA-Z]+|--[a-zA-Z-]+)(?:\s+\'(.*?)\'|\s+\"(.*?)\"|\s+([^\s-][^ ]*?)|\s+(\d[\d,]*))?/';

        preg_match_all($pattern, $cli_command, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $key = $match[1]; // The flag (e.g., -u, --preset-import-file)
            $value = $match[2] ?? $match[3] ?? $match[4] ?? $match[5] ?? null; // Handle different cases for the value

            // Handle multiple occurrences for some flags (e.g., --aencoder, --ab)
            if (isset($arguments[$key])) {
                $arguments[$key] = (array) $arguments[$key];
                $arguments[$key][] = $value;
            } else {
                $arguments[$key] = $value;
            }
        }

        info($arguments);

        return $arguments;
    }

}
