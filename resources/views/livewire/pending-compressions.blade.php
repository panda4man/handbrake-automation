<div wire:poll.3s="fetchPendingCompressions">
    <div class="pb-4">
        @if($pending_compressions->isNotEmpty())
            <button
                class="rounded-md bg-indigo-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                wire:click="startNextCompression"
            >
                Start Next Compression
            </button>
        @endif
    </div>

    @if ($pending_compressions->isEmpty())
        <div class="flex items-center space-x-2 p-4 bg-gray-100 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>

            <p class="text-blue-600 font-medium">No pending compressions at the moment.</p>
        </div>
    @else
        <ul class="space-y-2">
            @foreach($pending_compressions as $compression)
                <li class="p-4 bg-gray-100 rounded shadow">
                    <strong>{{ $compression->file_name }}</strong>
                    <p>Type: {{ $compression->file_type }}</p>
                    <p>Size: {{ number_format($compression->file_size_before / 1024 / 1024, 2) }} MB</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
