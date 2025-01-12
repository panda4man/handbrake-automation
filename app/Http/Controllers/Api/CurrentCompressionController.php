<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileCompression;
use Illuminate\Http\Request;

class CurrentCompressionController extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse|\App\Http\Resources\FileCompression
    {
        $current = FileCompression::active()->first();

        if(!$current) {
            return response()->json([
                'message' => 'No active compression',
            ], 404);
        }

        return \App\Http\Resources\FileCompression::make($current);
    }
}
