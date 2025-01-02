<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('File Compression Dashboard') }}
        </h2>
    </x-slot>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Current Job Status Column -->
            <div class="col-span-1 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-semibold mb-4">Current Job Status</h2>
                <livewire:current-compression-status />
            </div>

            <!-- Pending Compressions Column -->
            <div class="col-span-1 bg-white rounded-lg shadow-md p-4">
                <livewire:pending-compressions />
            </div>

            <!-- Completed Compressions Column -->
            <div class="col-span-1 bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-semibold mb-4">Completed Compressions</h2>
                <livewire:completed-compressions />
            </div>

            <!-- Failed Compressions Column -->
            <div class="col-span-1 bg-white rounded-lg shadow-md p-4">
                <livewire:failed-compressions />
            </div>
        </div>
    </main>
</x-app-layout>
