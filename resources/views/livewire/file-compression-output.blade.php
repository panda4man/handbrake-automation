<div>
    <!-- Summary Section -->
    <div class="p-4 bg-gray-100 rounded shadow mb-6" wire:poll.3s="refreshStats">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Total Space Saved</p>
                <p class="text-xl font-bold text-blue-600">
                    {{ $total_space_saved }} GB
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Compression Time</p>
                <p class="text-xl font-bold text-blue-600">
                    {{ $this->formatDuration($total_compression_time) }}
                </p>
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'completed' }">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                <button
                    @click="activeTab = 'completed'"
                    :class="activeTab === 'completed' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="px-4 py-2 font-medium text-sm border-b-2 focus:outline-none">
                    Completed Compressions
                </button>

                <button
                    @click="activeTab = 'failed'"
                    :class="activeTab === 'failed' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="px-4 py-2 font-medium text-sm border-b-2 focus:outline-none">
                    Failed Compressions
                </button>
            </nav>
        </div>

        <div x-show="activeTab === 'completed'" x-cloak class="mt-4">
            <livewire:completed-compressions />
        </div>

        <div x-show="activeTab === 'failed'" x-cloak class="mt-4">
            <livewire:failed-compressions />
        </div>
    </div>
</div>
