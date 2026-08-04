<?php

use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\EnquiryController as AgentEnquiryController;
use App\Http\Controllers\Agent\GalleryController;
use App\Http\Controllers\Agent\PropertyController as AgentPropertyController;
use App\Http\Controllers\Agent\VideoUploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Agent Routes
|--------------------------------------------------------------------------
| Required by: resources/views/agent/*.blade.php (Phase 6).
| Controllers implemented in Phase 12, CRUD wired in Phase 14,
| media upload wired in Phase 16/17.
*/

Route::middleware(['auth', 'role:agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {

        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

        // Resourceful property CRUD -> agent.properties.index/create/store/edit/update/destroy
        Route::resource('properties', AgentPropertyController::class)->except(['show']);

        // Remove a single gallery image (used inline inside the edit form, Phase 6)
        Route::delete('/properties/gallery/{gallery}', [GalleryController::class, 'destroy'])
            ->name('properties.gallery.destroy');

        // Chunked virtual-tour video upload — called by video-upload.js while the
        // agent is still filling out the Add/Edit Property form (Phase 17).
        Route::post('/properties/video-chunk', [VideoUploadController::class, 'storeChunk'])
            ->name('properties.video-chunk');

        Route::get('/enquiries', [AgentEnquiryController::class, 'index'])->name('enquiries');

        // AJAX status update (rep-enquiry-status <select> in agent/enquiries.blade.php)
        Route::patch('/enquiries/{enquiry}/status', [AgentEnquiryController::class, 'updateStatus'])
            ->name('enquiries.update-status');
    });
