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
| Agent dashboard, property CRUD, gallery management,
| video upload and enquiries.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [AgentDashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Property CRUD
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | {property:id} forces Laravel to find Property using ID,
        | even though Property model uses slug as its default route key.
        |
        | Example:
        | /agent/properties/2/edit
        |
        */

        Route::get('/properties', [AgentPropertyController::class, 'index'])
            ->name('properties.index');

        Route::get('/properties/create', [AgentPropertyController::class, 'create'])
            ->name('properties.create');

        Route::post('/properties', [AgentPropertyController::class, 'store'])
            ->name('properties.store');

        Route::get('/properties/{property:id}/edit', [AgentPropertyController::class, 'edit'])
            ->name('properties.edit');

        Route::put('/properties/{property:id}', [AgentPropertyController::class, 'update'])
            ->name('properties.update');

        Route::patch('/properties/{property:id}', [AgentPropertyController::class, 'update'])
            ->name('properties.update');

        Route::delete('/properties/{property:id}', [AgentPropertyController::class, 'destroy'])
            ->name('properties.destroy');


        /*
        |--------------------------------------------------------------------------
        | Gallery Image Delete
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/properties/gallery/{gallery}',
            [GalleryController::class, 'destroy']
        )->name('properties.gallery.destroy');


        /*
        |--------------------------------------------------------------------------
        | Chunked Virtual Tour Video Upload
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/properties/video-chunk',
            [VideoUploadController::class, 'storeChunk']
        )->name('properties.video-chunk');


        /*
        |--------------------------------------------------------------------------
        | Agent Enquiries
        |--------------------------------------------------------------------------
        */

        Route::get('/enquiries', [AgentEnquiryController::class, 'index'])
            ->name('enquiries');


        /*
        |--------------------------------------------------------------------------
        | Update Enquiry Status
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/enquiries/{enquiry}/status',
            [AgentEnquiryController::class, 'updateStatus']
        )->name('enquiries.update-status');

    });