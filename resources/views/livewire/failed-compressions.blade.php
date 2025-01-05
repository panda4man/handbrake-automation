<div wire:poll.3s="loadFailedCompressions">
    @if ($failed_compressions->isEmpty())
        <div class="flex items-center space-x-2 p-4 bg-gray-100 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            <p class="text-red-600 font-medium">No failed compressions at the moment.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">File Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Error Message</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($failed_compressions as $compression)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $compression->file_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $compression->error_message }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                <button 
                                    wire:click="retryCompression({{ $compression->id }})"
                                    class="px-3 py-1 text-white bg-red-600 rounded hover:bg-red-700">
                                    Retry
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
