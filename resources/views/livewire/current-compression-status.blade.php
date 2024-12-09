<div wire:poll.10s="updateCompressionStatus">
    <h2 class="text-lg font-semibold mb-4">Current Compression Status</h2>
    @if ($current_status)
        <div class="p-4 bg-blue-100 rounded shadow">
            <p><strong>Progress:</strong> {{ $current_status['progress'] ?? 'N/A' }}</p>
            <p><strong>ETA:</strong> {{ $current_status['eta'] ?? 'N/A' }}</p>
            <p><strong>Elapsed Time:</strong> {{ $current_status['elapsed_time'] ?? 'N/A' }}</p>
            <p><strong>CPU Usage:</strong> {{ $current_status['cpu_usage'] ?? 'N/A' }}</p>
            <p><strong>Memory Usage:</strong> {{ $current_status['memory_usage'] ?? 'N/A' }}</p>
        </div>
    @else
        <p>No active compression at the moment.</p>
    @endif
</div>
