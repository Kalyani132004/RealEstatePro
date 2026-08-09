<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Store normal images
     */
    public function store(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1920,
        int $quality = 90
    ): string {

        $image = $this->manager
            ->read($file->getRealPath())
            ->orient();

        if ($image->width() > $maxWidth) {
            $image->scaleDown(width: $maxWidth);
        }

        $path = trim($directory, '/') . '/' . Str::uuid() . '.webp';

        Storage::disk('public')->put(
            $path,
            (string) $image->encode(new WebpEncoder(quality: $quality))
        );

        return $path;
    }

    /**
     * Store image + thumbnail
     */
    public function storeWithThumbnail(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1920,
        int $thumbWidth = 400,
        int $quality = 90
    ): array {

        $original = $this->manager
            ->read($file->getRealPath())
            ->orient();

        $thumbnail = clone $original;

        if ($original->width() > $maxWidth) {
            $original->scaleDown(width: $maxWidth);
        }

        $thumbnail->cover($thumbWidth, intval($thumbWidth * 0.75));

        $base = trim($directory, '/') . '/' . Str::uuid();

        $path = $base . '.webp';
        $thumb = $base . '_thumb.webp';

        Storage::disk('public')->put(
            $path,
            (string) $original->encode(new WebpEncoder(quality: $quality))
        );

        Storage::disk('public')->put(
            $thumb,
            (string) $thumbnail->encode(new WebpEncoder(quality: $quality))
        );

        return [
            'path' => $path,
            'thumbnail_path' => $thumb,
        ];
    }

    /**
     * Store Avatar
     */
    public function storeAvatar(
        UploadedFile $file,
        string $directory = 'avatars',
        int $size = 500
    ): string {

        $image = $this->manager
            ->read($file->getRealPath())
            ->orient()
            ->cover($size, $size);

        $path = trim($directory, '/') . '/' . Str::uuid() . '.webp';

        Storage::disk('public')->put(
            $path,
            (string) $image->encode(new WebpEncoder(quality: 95))
        );

        return $path;
    }

    /**
     * Delete image
     */
    public function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        Storage::disk('public')->delete($path);

        $thumb = str_replace('.webp', '_thumb.webp', $path);

        if (Storage::disk('public')->exists($thumb)) {
            Storage::disk('public')->delete($thumb);
        }
    }
}