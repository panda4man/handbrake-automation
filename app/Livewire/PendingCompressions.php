<?php

namespace App\Livewire;

use App\Models\FileCompression;
use Livewire\Component;

class PendingCompressions extends Component
{
    public $pending_compressions = [];

    public function mount(): void
    {
        $this->fetchPendingCompressions();
    }

    public function fetchPendingCompressions(): void
    {
        $this->pending_compressions = FileCompression::pending()->get();
    }

    public function startNextCompression(): void
    {
        // Trigger the CLI watcher for the next pending compression
        $compression = FileCompression::pending()->first();

        if ($compression) {
            $compression->compress();

            session()->flash('info', 'Watcher started for pending compressions.');
        } else {
            session()->flash('info', 'No pending compressions to start.');
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('livewire.pending-compressions', [
            'pending_compressions' => $this->pending_compressions,
        ]);
    }
}
