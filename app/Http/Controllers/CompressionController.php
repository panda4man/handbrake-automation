<?php

namespace App\Http\Controllers;

use App\Actions\FetchHandbrakeStatus;
use App\Jobs\ProcessFileJob;
use App\Models\FileCompression;
use Illuminate\Http\Request;

class CompressionController
{
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'job_id' => 'required|exists:file_compressions,id',
            'status' => 'required|in:success,failure',
            'file_size_after' => 'nullable|integer',
        ]);

        $compression = FileCompression::find($request->job_id);

        $compression->update([
            'failed_at' => $request->status === 'failure' ? now() : null,
            'file_size_after' => $request->file_size_after,
            'active' => false, // Mark the current job as inactive
        ]);

        // Trigger the next job in the queue
        $nextJob = FileCompression::where('active', false)
            ->whereNull('failed_at') // Ensure the job isn't failed
            ->whereNull('file_size_after') // Ensure it's not completed
            ->where('id', '<>', $compression->id) // Ensure it's not the current job
            ->first();

        if ($nextJob) {
            $nextJob->update(['active' => true]); // Mark the job as active
            ProcessFileJob::dispatch($nextJob);
        }

        return response()->json(['message' => 'Job updated successfully.']);
    }
}
