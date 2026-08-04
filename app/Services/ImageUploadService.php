<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

/**
 * Centralizes every image upload in the app (property covers, gallery
 * images, floor plans, avatars) so resizing/compression/thumbnail logic
 * lives in exactly one place instead of being copy-pasted across
 * Agent\PropertyController, User\ProfileController, etc.
 *
 * All images are re-encoded to WebP: ~25-35% smaller than JPEG at
 * equivalent visual quality, and supported by every modern browser
 * this app targets (Bootstrap 5 baseline).
 */
class ImageUploadService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Resize (down only, never up-scaled) + compress + store a single image.
     * Returns the storage path (relative to the 'public' disk).
     */
    public function store(UploadedFile $file, string $directory, int $maxWidth = 1920, int $quality = 82): string
    {
        $image = $this->manager->read($file->getRealPath());

        if ($image->width() > $maxWidth) {
            $image->scaleDown(width: $maxWidth);
        }

        $path = trim($directory, '/') . '/' . Str::uuid() . '.webp';

        Storage::disk('public')->put($path, (string) $image->encode(new WebpEncoder(quality: $quality)));

        return $path;
    }

    /**
     * Store a full-size display image AND a smaller thumbnail (used for the
     * property-details gallery strip / lightbox thumbnails, Phase 8).
     * Both share the same base filename so they stay easy to reason about
     * and clean up together in delete().
     *
     * @return array{path: string, thumbnail_path: string}
     */
    public function storeWithThumbnail(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1920,
        int $thumbWidth = 400,
        int $quality = 82
    ): array {
        $original = $this->manager->read($file->getRealPath());
        $thumbnail = clone $original;

        if ($original->width() > $maxWidth) {
            $original->scaleDown(width: $maxWidth);
        }
        $thumbnail->cover($thumbWidth, (int) round($thumbWidth * 0.75)); // 4:3 crop, matches gallery grid aspect ratio

        $base = trim($directory, '/') . '/' . Str::uuid();
        $path = $base . '.webp';
        $thumbPath = $base . '_thumb.webp';

        Storage::disk('public')->put($path, (string) $original->encode(new WebpEncoder(quality: $quality)));
        Storage::disk('public')->put($thumbPath, (string) $thumbnail->encode(new WebpEncoder(quality: $quality)));

        return ['path' => $path, 'thumbnail_path' => $thumbPath];
    }

    /**
     * Square-cropped avatar, always resized to exactly $size x $size —
     * keeps every avatar consistently sized regardless of the source image.
     */
    public function storeAvatar(UploadedFile $file, string $directory = 'avatars', int $size = 300): string
    {
        $image = $this->manager->read($file->getRealPath())->cover($size, $size);

        $path = trim($directory, '/') . '/' . Str::uuid() . '.webp';

        Storage::disk('public')->put($path, (string) $image->encode(new WebpEncoder(quality: 85)));

        return $path;
    }

    /**
     * Delete a stored image and, if present, its sibling "_thumb" variant.
     */
    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);

        $thumbPath = preg_replace('/\.webp$/', '_thumb.webp', $path);
        if ($thumbPath !== $path && Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }
    }
}
