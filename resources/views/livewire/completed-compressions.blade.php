<div wire:poll.3s="fetchCompletedCompressions">
    @if ($completed_compressions->isEmpty())
        <div class="flex items-center space-x-2 p-4 bg-gray-100 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>

            <p class="text-blue-600 font-medium">No completed compressions at the moment.</p>
        </div>
    @else
        <ul>
            @foreach($completed_compressions as $compression)
                <li class="mb-2 p-4 border border-gray-200 rounded-lg shadow-sm">
                    <div>
                        <strong>File:</strong> {{ $compression->file_name }}
                    </div>
                    <div>
                        <strong>Original Size:</strong> {{ number_format($compression->file_size_before / 1048576, 2) }} MB
                    </div>
                    <div>
                        <strong>Compressed Size:</strong> {{ $compression->file_size_after }} MB
                    </div>
                    <div>
                        <strong>Compression Ratio:</strong> {{ $compression->compression_ratio }}%
                    </div>
                    <div>
                        <strong>Time to Complete:</strong> {{ $compression->completed_at->diffForHumans($compression->started_at, true) }}
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
