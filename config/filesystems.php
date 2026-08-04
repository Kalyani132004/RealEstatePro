<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    | RealEstatePro stores property cover images, gallery images, virtual
    | tour videos, floor plans, and user avatars via Laravel Storage.
    | Default is 'public' for local/dev; switch FILESYSTEM_DISK=s3 in .env
    | for AWS production (Phase 20) without changing any application code,
    | since every upload in the app is written through Storage::disk('public')
    | using the disk name from config, never a hardcoded path.
    */

    'default' => env('FILESYSTEM_DISK', 'public'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    | Run `php artisan storage:link` once after installation. This makes
    | storage/app/public accessible at public/storage — every asset URL in
    | the Blade views (asset('storage/' . $property->cover_image), etc.)
    | depends on this symlink existing.
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
