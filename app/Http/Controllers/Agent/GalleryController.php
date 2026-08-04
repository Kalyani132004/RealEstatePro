<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\ImageUploadService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class GalleryController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ImageUploadService $images)
    {
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        // A gallery image inherits its parent property's authorization rules.
        $this->authorize('manageMedia', $gallery->property);

        // Deletes both the full-size image and its "_thumb" sibling (Phase 16).
        $this->images->delete($gallery->image_path);
        $gallery->delete();

        return back()->with('success', 'Image removed.');
    }
}
