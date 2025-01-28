<?php

namespace App\Livewire;

use App\Models\FileCompression;
use Livewire\Component;
use Livewire\WithPagination;

class CompletedCompressions extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->fetchCompletedCompressions();
    }

    public function fetchCompletedCompressions()
    {
        return FileCompression::completed()
        ->orderByDesc('completed_at')
        ->paginate(20);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('livewire.completed-compressions', [
            'completed_compressions' => $this->fetchCompletedCompressions(),
        ]);
    }
}
