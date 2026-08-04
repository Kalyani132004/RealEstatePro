<?php

use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Required by: resources/views/admin/*.blade.php (Phase 7).
| Controllers implemented in Phase 12, CRUD wired in Phase 14.
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Agents
        Route::get('/agents', [AdminAgentController::class, 'index'])->name('agents.index');
        Route::patch('/agents/{agent}/verify', [AdminAgentController::class, 'toggleVerify'])->name('agents.toggle-verify');

        // Properties (platform-wide moderation)
        Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
        Route::patch('/properties/{property}/feature', [AdminPropertyController::class, 'toggleFeature'])->name('properties.toggle-feature');
        Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

        // Categories -> admin.categories.index/store/update/destroy
        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

        // Locations -> admin.locations.index/store/update/destroy
        Route::resource('locations', LocationController::class)->except(['create', 'edit', 'show']);

        // Enquiries (platform-wide, read-only)
        Route::get('/enquiries', [AdminEnquiryController::class, 'index'])->name('enquiries.index');
    });
