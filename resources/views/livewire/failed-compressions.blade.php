<div>
    <h2 class="text-xl font-bold mb-4">Failed Compressions</h2>

    @if (session()->has('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-2">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-200 text-red-800 p-2 rounded mb-2">
            {{ session('error') }}
        </div>
    @endif

    @if ($failed_compressions->isEmpty())
        <div class="flex items-center space-x-2 p-4 bg-gray-100 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>

            <p class="text-red-800 font-medium">No pending compressions at the moment.</p>
        </div>
    @else
        <ul class="space-y-4">
            @foreach ($failed_compressions as $compression)
                <li class="p-4 bg-white rounded shadow flex items-center justify-between">
                    <div>
                        <p class="font-semibold">File: {{ $compression->file_name }}</p>
                        <p class="text-sm text-gray-600">
                            Failed At: {{ $compression->failed_at->format('Y-m-d H:i:s') }}
                        </p>
                    </div>
                    <button
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        wire:click="retryCompression({{ $compression->id }})"
                    >
                        Retry
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
