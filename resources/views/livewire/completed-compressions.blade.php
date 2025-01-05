<div x-data="{ showModal: false, cliCommand: '' }" wire:poll.3s="fetchCompletedCompressions">
    @if ($completed_compressions->isEmpty())
            <div class="flex items-center space-x-2 p-4 bg-gray-100 rounded shadow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <p class="text-blue-600 font-medium">No completed compressions at the moment.</p>
            </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">File Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Type</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Original Size</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Compressed Size</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Compression Ratio</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Completed At</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Time to Complete</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($completed_compressions as $compression)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $compression->file_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $compression->file_type }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ number_format($compression->file_size_before / 1024 / 1024, 2) }} MB
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ number_format($compression->file_size_after / 1024 / 1024, 2) }} MB
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ round(($compression->file_size_after / $compression->file_size_before) * 100, 2) }}%
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ $compression->completed_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ $compression->completed_at->diffForHumans($compression->started_at, true) }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                <button 
                                    @click="cliCommand = '{{ addslashes($compression->cli_command) }}'; showModal = true"
                                    class="px-3 py-1 text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Modal -->
    <div 
        x-show="showModal" 
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-transition>
        <div class="bg-white rounded-lg shadow-lg w-[48rem] p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">CLI Command</h3>
            <textarea 
                x-model="cliCommand" 
                readonly 
                rows="10" 
                class="w-full border rounded p-2 text-sm text-gray-700 bg-gray-100">
            </textarea>
            <div class="flex justify-end mt-4 space-x-2">
                <button 
                    @click="showModal = false"
                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
