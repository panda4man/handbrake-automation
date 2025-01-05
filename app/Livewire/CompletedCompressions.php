<?php

namespace App\Livewire;

use App\Models\FileCompression;
use Livewire\Component;

class CompletedCompressions extends Component
{
    public $completed_compressions = [];

    public function mount(): void
    {
        $this->fetchCompletedCompressions();
    }

    public function fetchCompletedCompressions(): void
    {
        $this->completed_compressions = FileCompression::completed()->orderByDesc('completed_at')->get();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('livewire.completed-compressions', [
            'completed_compressions' => $this->completed_compressions,
        ]);
    }
}
