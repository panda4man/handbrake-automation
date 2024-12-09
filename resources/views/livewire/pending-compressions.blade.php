<div>
    <h2 class="text-lg font-semibold mb-4">Pending Compressions</h2>
    @if($pending_compressions->isEmpty())
        <p>No pending compressions at the moment.</p>
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
