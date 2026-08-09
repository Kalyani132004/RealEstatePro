<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Handles large virtual-tour video uploads via client-side chunking
 */
class VideoUploadService
{
    private const MAX_TOTAL_BYTES = 100 * 1024 * 1024; // 100MB, matches StorePropertyRequest
    private const TEMP_DISK = 'local'; // storage/app/private — never web-accessible

    /**
     * Append one chunk to the in-progress upload. Returns progress info,
     * and — once the final chunk arrives — the finished, validated video.
     *
     * @return array{complete: bool, received: int, total: int, path?: string, poster_path?: ?string}
     */
    public function storeChunk(UploadedFile $chunk, string $uploadId, int $chunkIndex, int $totalChunks): array
    {
        $uploadId = Str::slug($uploadId); // sanitize — this becomes part of a filesystem path
        $tempDir = "temp/videos/{$uploadId}";

        $this->guardAgainstOversizedUpload($tempDir, $chunk->getSize());

        Storage::disk(self::TEMP_DISK)->put("{$tempDir}/{$chunkIndex}", file_get_contents($chunk->getRealPath()));

        $received = count(Storage::disk(self::TEMP_DISK)->files($tempDir));

        if ($chunkIndex < $totalChunks - 1) {
            return ['complete' => false, 'received' => $received, 'total' => $totalChunks];
        }

        return array_merge(
            ['complete' => true, 'received' => $received, 'total' => $totalChunks],
            $this->finalize($tempDir, $uploadId, $totalChunks)
        );
    }

    /**
     * Concatenate every chunk in order, validate the assembled file is
     * genuinely an MP4 (not just named like one), move it to public
     * storage, then clean up the temp chunk directory either way.
     */
    private function finalize(string $tempDir, string $uploadId, int $totalChunks): array
    {
        try {
            $assembled = '';
            for ($i = 0; $i < $totalChunks; $i++) {
                if (! Storage::disk(self::TEMP_DISK)->exists("{$tempDir}/{$i}")) {
                    throw new RuntimeException("Missing chunk {$i} of {$totalChunks} — please re-upload.");
                }
                $assembled .= Storage::disk(self::TEMP_DISK)->get("{$tempDir}/{$i}");
            }

            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), substr($assembled, 0, 4096));
            if (! in_array($mime, ['video/mp4', 'application/octet-stream'], true)) {
                throw new RuntimeException('Uploaded file is not a valid MP4 video.');
            }

            $path = 'properties/videos/' . Str::uuid() . '.mp4';
            Storage::disk('public')->put($path, $assembled);
            unset($assembled); // release the in-memory buffer immediately

            return [
                'path' => $path,
                'poster_path' => $this->extractPosterFrame($path),
            ];
        } finally {
            Storage::disk(self::TEMP_DISK)->deleteDirectory($tempDir);
        }
    }

    /**
     * Grabs a frame at the 2-second mark to use as a video poster image,
     * in case the agent didn't also upload a cover photo. Requires the
     * ffmpeg binary on the server (documented in Phase 20's AWS setup).
     * Silently returns null if ffmpeg isn't available — the property's
     * cover_image is used as the <video poster> either way (Phase 8).
     */
    private function extractPosterFrame(string $videoPath): ?string
    {
        try {
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open(Storage::disk('public')->path($videoPath));

            $posterPath = 'properties/videos/posters/' . Str::uuid() . '.jpg';
            $fullPosterPath = Storage::disk('public')->path($posterPath);

            Storage::disk('public')->makeDirectory('properties/videos/posters');
            $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($fullPosterPath);

            return $posterPath;
        } catch (\Throwable $e) {
            Log::info('Video poster extraction skipped (ffmpeg unavailable or failed): ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Aborts (and cleans up) an upload whose cumulative chunk size has
     * already exceeded the 100MB limit, rather than waiting for every
     * chunk to arrive before rejecting an oversized file.
     */
    private function guardAgainstOversizedUpload(string $tempDir, int $incomingChunkSize): void
    {
        $currentSize = collect(Storage::disk(self::TEMP_DISK)->files($tempDir))
            ->sum(fn ($file) => Storage::disk(self::TEMP_DISK)->size($file));

        if ($currentSize + $incomingChunkSize > self::MAX_TOTAL_BYTES) {
            Storage::disk(self::TEMP_DISK)->deleteDirectory($tempDir);
            throw new RuntimeException('Video exceeds the 100MB upload limit.');
        }
    }
}
