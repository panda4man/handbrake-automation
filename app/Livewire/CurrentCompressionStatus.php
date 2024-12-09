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
        $this->active_compression = FileCompression::whereNotNull('started_at')
            ->whereNull('failed_at')
            ->whereNull('file_size_after')
            ->first();

        if ($this->active_compression) {
            $this->updateCompressionStatus();
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
}
