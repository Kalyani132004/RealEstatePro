<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\VideoUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class VideoUploadController extends Controller
{
    public function __construct(protected VideoUploadService $videos)
    {
    }

    /**
     * Receives one chunk of a virtual tour video from video-upload.js.
     * Called repeatedly (once per chunk) while the agent is filling out
     * the Add/Edit Property form, *before* the form itself is submitted —
     * the form only carries the resulting file path, not the raw video.
     */
    public function storeChunk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chunk' => ['required', 'file'],
            'upload_id' => ['required', 'string', 'max:100'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        try {
            $result = $this->videos->storeChunk(
                $request->file('chunk'),
                $validated['upload_id'],
                $validated['chunk_index'],
                $validated['total_chunks']
            );

            return response()->json($result);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
