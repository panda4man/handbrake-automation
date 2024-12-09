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
        $this->pending_compressions = FileCompression::whereNull('started_at')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('livewire.pending-compressions', [
            'pending_compressions' => $this->pending_compressions,
        ]);
    }
}
