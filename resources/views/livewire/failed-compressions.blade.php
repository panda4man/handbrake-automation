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
        <p class="text-gray-600">No failed compressions at the moment.</p>
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
