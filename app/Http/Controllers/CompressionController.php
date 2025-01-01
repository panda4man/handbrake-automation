<?php

namespace App\Http\Controllers;

use App\Actions\CompressFile;
use App\Models\FileCompression;
use Illuminate\Http\Request;

class CompressionController
{
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'job_id' => 'required|exists:file_compressions,id',
            'status' => 'required|in:success,failure,processing',
            'pid' => 'nullable|integer',
        ]);

        /** @var FileCompression $compression */
        $compression = FileCompression::findOrFail($request->job_id);

        if($request->status === 'processing') {
            $compression->update(['pid' => $request->pid]);

            return response()->json(['message' => 'Job updated successfully.']);
        } else {
            $compression->update([
                'failed_at' => $request->status === 'failure' ? now() : null,
                'completed_at' => $request->status === 'success' ? now() : null,
                'active' => false, // Mark the current job as inactive
            ]);

            // Trigger the next job in the queue
            $next_job = FileCompression::pending()
                                      ->where('id', '<>', $compression->id) // Ensure it's not the current job
                                      ->first();

            $message = "Job updated successfully.";

            if ($next_job) {
                $next_job->update(['active' => true]); // Mark the job as active
                (new CompressFile)->handle($next_job);

                $message .= " Kicked off a new job.";
            }

            return response()->json(['message' => $message]);
        }
    }
}
