<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminListingController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// PUBLIC
// -------------------------------------------------------
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/listings/{listing:slug}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/categories/{category:slug}', [ListingController::class, 'byCategory'])->name('listings.category');
Route::get('/search', [ListingController::class, 'search'])->name('listings.search');

// -------------------------------------------------------
// AUTH (Laravel Breeze/Fortify akan generate ini,
// tapi explisit di sini untuk kejelasan)
// -------------------------------------------------------
// Route::get('/login', ...) dsb — di-handle Breeze

// Email verification
Route::get('/email/verify', [VerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// -------------------------------------------------------
// AUTHENTICATED (buyer + seller + admin)
// -------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // Bookmark
    Route::post('/bookmarks/{listing}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Report
    Route::post('/reports/{listing}', [ReportController::class, 'store'])->name('reports.store');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');

    // Upgrade ke seller
    Route::get('/seller/register', [SellerController::class, 'registerForm'])->name('seller.register');
    Route::post('/seller/register', [SellerController::class, 'submitRegister'])->name('seller.register.submit');
    Route::get('/seller/status', [SellerController::class, 'status'])->name('seller.status');
});

// -------------------------------------------------------
// SELLER ONLY
// -------------------------------------------------------
Route::middleware(['auth', 'verified', 'role:seller'])->group(function () {

    // Dashboard seller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Listing
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing:slug}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing:slug}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing:slug}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::patch('/listings/{listing:slug}/status', [ListingController::class, 'updateStatus'])->name('listings.status');
});

// -------------------------------------------------------
// ADMIN ONLY
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn () => redirect()->route('admin.users.index'));

    // Users
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'destroy']);

    // Listings
    Route::resource('listings', AdminListingController::class)->only(['index', 'show', 'destroy']);
    Route::patch('listings/{listing}/status', [AdminListingController::class, 'updateStatus'])->name('listings.status');

    // Categories
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // Reports
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::patch('reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.status');

    // Seller verifications
    Route::get('verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::patch('verifications/{verification}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::patch('verifications/{verification}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');
});
