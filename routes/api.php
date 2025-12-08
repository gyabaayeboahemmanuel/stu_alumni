<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AlumniDashboardController;
use App\Http\Controllers\BusinessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

// SIS Integration API (for external SIS system)
Route::prefix('sis')->name('sis.')->group(function () {
    Route::post('/verify-alumni', function (Request $request) {
        // This endpoint would be called by the SIS system to verify alumni data
        // Implementation would depend on the SIS API specification
        return response()->json([
            'success' => false,
            'message' => 'SIS integration not implemented yet'
        ], 501);
    })->name('verify-alumni');
});

// Authenticated API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Alumni API routes
    Route::prefix('alumni')->name('alumni.')->group(function () {
        // Get alumni profile (using Resource)
        Route::get('/profile', function (Request $request) {
            return new \App\Http\Resources\AlumniResource($request->user()->alumni->load('user', 'businesses'));
        })->name('profile.api');
        
        // Update alumni profile (using Form Request)
        Route::put('/profile', [AlumniDashboardController::class, 'update'])
            ->name('profile.update.api');
        
        // Get alumni's event registrations (API version)
        Route::get('/my-registrations', [AlumniDashboardController::class, 'getMyRegistrations'])
            ->name('registrations.api');
        
        // Business API routes
        Route::apiResource('businesses', BusinessController::class)->except(['create', 'edit']);
        
        // Events API routes
        Route::get('/events', [AlumniDashboardController::class, 'events']);
        Route::post('/events/{event}/register', [AlumniDashboardController::class, 'registerForEvent']);
        
        // Legacy routes (keep for backward compatibility if needed)
        Route::get('/profile-legacy', function (Request $request) {
            return $request->user()->alumni->load('user');
        })->name('profile.legacy');
        
        Route::put('/profile-legacy', [AlumniDashboardController::class, 'updateProfile']);
        
        Route::get('/my-registrations-legacy', [AlumniDashboardController::class, 'myRegistrations']);
    });
});

// Admin API Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Statistics API
    Route::get('/dashboard-stats', function () {
        // Return dashboard statistics
        return response()->json([
            'total_alumni' => \App\Models\Alumni::count(),
            'verified_alumni' => \App\Models\Alumni::verified()->count(),
            'pending_verification' => \App\Models\Alumni::pending()->count(),
            'total_businesses' => \App\Models\Business::count(),
            'pending_businesses' => \App\Models\Business::where('status', 'pending')->count(),
        ]);
    })->name('dashboard.stats');
    
    // Alumni Management API
    Route::apiResource('alumni', \App\Http\Controllers\Admin\AlumniManagementController::class);
    
    // Bulk Actions API
    Route::post('/alumni/bulk-verify', function (Request $request) {
        $alumniIds = $request->input('alumni_ids', []);
        $count = \App\Models\Alumni::whereIn('id', $alumniIds)->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verification_source' => 'manual'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "{$count} alumni verified successfully",
            'verified_count' => $count
        ]);
    })->name('alumni.bulk-verify');
});