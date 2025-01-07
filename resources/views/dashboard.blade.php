<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('File Compression Dashboard') }}
        </h2>
    </x-slot>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div x-data="{ activeTab: 'current' }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                        <button
                            @click="activeTab = 'current'"
                            :class="activeTab === 'current' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-4 py-2 font-medium text-sm border-b-2 focus:outline-none">
                            Current Compressions
                        </button>

                        <button
                            @click="activeTab = 'pending'"
                            :class="activeTab === 'pending' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="px-4 py-2 font-medium text-sm border-b-2 focus:outline-none">
                            Pending Compressions
                        </button>
                    </nav>
                </div>

                <div x-show="activeTab === 'current'" x-cloak class="mt-4">
                    <livewire:current-compression-status />
                </div>

                <div x-show="activeTab === 'pending'" x-cloak class="mt-4">
                    <livewire:pending-compressions />
                </div>
            </div>

            <!-- Completed Compressions Column -->
            <div class="col-span-3 bg-white rounded-lg shadow-md p-4">
                <livewire:file-compression-output />
            </div>
        </div>
    </main>
</x-app-layout>
