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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// AUTH — di-handle Breeze
// -------------------------------------------------------
require __DIR__ . '/auth.php';

// -------------------------------------------------------
// EMAIL VERIFICATION
// -------------------------------------------------------
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
// PUBLIC
// -------------------------------------------------------
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/search', [ListingController::class, 'search'])->name('listings.search');
Route::get('/categories/{category:slug}', [ListingController::class, 'byCategory'])->name('listings.category');

// -------------------------------------------------------
// AUTHENTICATED — semua role
// -------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
Route::middleware(['auth', 'role:seller'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Listing — create HARUS sebelum {listing:slug}
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

    Route::get('/', fn() => redirect()->route('admin.users.index'));

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Listings
    Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/{listing}', [AdminListingController::class, 'show'])->name('listings.show');
    Route::patch('/listings/{listing}/status', [AdminListingController::class, 'updateStatus'])->name('listings.status');
    Route::delete('/listings/{listing}', [AdminListingController::class, 'destroy'])->name('listings.destroy');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.status');

    // Seller Verifications
    Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::patch('/verifications/{verification}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::patch('/verifications/{verification}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');
});

// -------------------------------------------------------
// PUBLIC — listings.show PALING BAWAH
// supaya tidak menangkap /listings/create sebagai slug
// -------------------------------------------------------
Route::get('/listings/{listing:slug}', [ListingController::class, 'show'])->name('listings.show');
