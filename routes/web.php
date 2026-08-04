<?php

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SavedPropertyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Registered via bootstrap/app.php -> withRouting(web: routes/web.php).
| Controllers referenced here are implemented in Phase 12 (Controllers) and
| wired to real data in Phases 13-18. This file defines the final route
| *contract* that every Blade view from Phases 3-8 already relies on.
*/

/* ============================ VISITOR ============================ */

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/properties', [PropertyController::class, 'search'])->name('properties.search');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

// Guests and authenticated users can both submit an enquiry (Phase 14/18)
Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');

/* ==================== AUTHENTICATED (ANY ROLE) ==================== */
// Profile & password are shared across User, Agent, and Admin dashboards
// (all three sidebars link to these same two routes).

Route::middleware('auth')->group(function () {
    Route::post('/saved-properties/toggle', [SavedPropertyController::class, 'toggle'])->name('saved-properties.toggle');

    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('user.profile');
    Route::put('/user/profile', [ProfileController::class, 'update'])->name('user.profile.update');

    Route::get('/user/password', [PasswordController::class, 'edit'])->name('user.password');
    Route::put('/user/password', [PasswordController::class, 'update'])->name('user.password.update');
});

/* ============================ USER ROLE ============================ */

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/user/saved-properties', [SavedPropertyController::class, 'index'])->name('user.saved-properties');
    Route::get('/user/enquiries', [UserDashboardController::class, 'enquiries'])->name('user.enquiries');
});

/* ==================== ROLE-SPECIFIC ROUTE FILES ==================== */

require __DIR__ . '/auth.php';
require __DIR__ . '/agent.php';
require __DIR__ . '/admin.php';
