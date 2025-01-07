<div wire:poll.750ms="fetchCurrentCompression">
    @if ($current_status)
        <div>
            <p><strong>File:</strong> {{ $active_compression['file_name'] ?? 'N/A' }}</p>
            <p><strong>Type:</strong> {{ $active_compression['file_type'] ?? 'N/A' }}</p>
            <p class="mt-4"><strong>Progress:</strong> {{ $current_status['progress'] ?? 'N/A' }}</p>
            <p><strong>ETA:</strong> {{ $current_status['eta'] ?? 'N/A' }}</p>
            <p><strong>Elapsed Time:</strong> {{ $current_status['elapsed_time'] ?? 'N/A' }}</p>
            <p><strong>CPU Usage:</strong> {{ $current_status['cpu_usage'] ?? 'N/A' }}</p>
            <p><strong>Memory Usage:</strong> {{ $current_status['memory_usage'] ?? 'N/A' }}</p>

            <div class="p-4 bg-gray-100 rounded shadow mt-4">
                <h3 class="text-lg font-bold mb-2">Command Details</h3>
                <div class="space-y-2">
                    @foreach ($parsed_args as $flag => $value)
                        <div class="flex">
                            <span class="font-medium text-gray-700">{{ $flag }}</span>
                            <span class="ml-2">
                                @if (is_array($value))
                                    {{ implode(', ', $value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center space-x-2 p-4 bg-gray-100 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>

            <p class="text-red-600 font-medium">No active compression jobs running currently.</p>
        </div>
    @endif
</div>
