<?php

namespace App\Livewire;

use App\Actions\CompressFile;
use App\Models\FileCompression;
use Livewire\Component;

class FailedCompressions extends Component
{
    public $failed_compressions = [];

    protected $listeners = ['compressionRetried' => 'loadFailedCompressions'];

    public function mount(): void
    {
        $this->loadFailedCompressions();
    }

    public function loadFailedCompressions(): void
    {
        $this->failed_compressions = FileCompression::failed()->get();
    }

    public function retryCompression($id): void
    {
        /** @var FileCompression $compression */
        $compression = FileCompression::find($id);

        if (!$compression) {
            session()->flash('error', 'Compression not found.');
            return;
        }

        // Reset relevant fields and dispatch the job again
        $compression->retry();

        // Trigger the compression action (replace with your queue logic if necessary)
        (new CompressFile)->handle($compression);

        session()->flash('success', 'Compression retry started.');
        $this->emit('compressionRetried');
    }

    public function render()
    {
        return view('livewire.failed-compressions');
    }
}
