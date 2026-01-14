<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AlumniDashboardController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AlumniManagementController as AdminAlumniController;
use App\Http\Controllers\Admin\AnnouncementManagementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\EventManagementController as AdminEventController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\YearGroupController as AdminYearGroupController;
use App\Http\Controllers\Admin\ChapterController as AdminChapterController;
use App\Http\Controllers\Admin\BroadcastController as AdminBroadcastController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register/sis', [AuthController::class, 'register'])->name('register.sis');
    Route::get('/register/manual', [AuthController::class, 'showManualRegistration'])->name('register.manual');
    
    // SIS Registration Process
    Route::post('/verify-sis', [AuthController::class, 'verifySIS'])->name('verify.sis');
    Route::post('/register/sis/complete', [AuthController::class, 'completeSISRegistration'])->name('register.sis.complete');
    
    // Manual Registration
    Route::post('/register/manual/process', [AuthController::class, 'processManualRegistration'])->name('register.manual.process');
    
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Public Business Directory
Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.public.index');
Route::get('/businesses/{slug}', [BusinessController::class, 'show'])->name('businesses.public.show');

// Donations
Route::get('/donate', [DonationController::class, 'create'])->name('donations.create');
Route::post('/donate', [DonationController::class, 'store'])->name('donations.store');

// Public Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/executives', function () {
    $executives = \App\Models\Executive::current()
        ->with('alumni.user')
        ->ordered()
        ->get();
    
    return view('executives', compact('executives'));
})->name('executives');

// Alumni Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Alumni Dashboard Routes
    Route::prefix('alumni')->name('alumni.')->group(function () {
        Route::get('/dashboard', [AlumniDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AlumniDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [AlumniDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/photo', [AlumniDashboardController::class, 'updateProfilePhoto'])->name('profile.photo.update');
        
        // Announcements
        Route::get('/announcements', [AlumniDashboardController::class, 'announcements'])->name('announcements');
        Route::get('/announcements/{slug}', [AlumniDashboardController::class, 'showAnnouncement'])->name('announcements.show');
        
        // Events
        Route::get('/events', [AlumniDashboardController::class, 'events'])->name('events');
        Route::post('/events/{event}/register', [AlumniDashboardController::class, 'registerForEvent'])->name('events.register');
        Route::get('/my-registrations', [AlumniDashboardController::class, 'myRegistrations'])->name('events.my-registrations');
        
        // Business Directory (Alumni-specific)
        Route::get('/my-businesses', [BusinessController::class, 'myBusinesses'])->name('businesses.my-businesses');
        Route::get('/businesses/create', [BusinessController::class, 'create'])->name('businesses.create');
        Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
        Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])->name('businesses.edit');
        Route::put('/businesses/{business}', [BusinessController::class, 'update'])->name('businesses.update');
        Route::delete('/businesses/{business}', [BusinessController::class, 'destroy'])->name('businesses.destroy');
    });
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/stats/alumni', [AdminDashboardController::class, 'alumniStats'])->name('alumni.stats');
    
    // Alumni Management
    Route::prefix('alumni')->name('alumni.')->group(function () {
        Route::get('/', [AdminAlumniController::class, 'index'])->name('index');
        Route::get('/{alumni}', [AdminAlumniController::class, 'show'])->name('show');
        Route::get('/{alumni}/edit', [AdminAlumniController::class, 'edit'])->name('edit');
        Route::put('/{alumni}', [AdminAlumniController::class, 'update'])->name('update');
        Route::post('/{alumni}/verify', [AdminAlumniController::class, 'verify'])->name('verify');
        Route::post('/{alumni}/reject', [AdminAlumniController::class, 'reject'])->name('reject');
        Route::delete('/{alumni}', [AdminAlumniController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [AdminAlumniController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // Announcement Management
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AdminAnnouncementController::class, 'index'])->name('index');
        Route::get('/create', [AdminAnnouncementController::class, 'create'])->name('create');
        Route::post('/', [AdminAnnouncementController::class, 'store'])->name('store');
        Route::get('/{announcement}/edit', [AdminAnnouncementController::class, 'edit'])->name('edit');
        Route::put('/{announcement}', [AdminAnnouncementController::class, 'update'])->name('update');
        Route::delete('/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('destroy');
        Route::post('/{announcement}/toggle-publish', [AdminAnnouncementController::class, 'togglePublish'])->name('toggle-publish');
        Route::post('/{announcement}/toggle-pin', [AdminAnnouncementController::class, 'togglePin'])->name('toggle-pin');
    });
    
    // Event Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('index');
        Route::get('/create', [AdminEventController::class, 'create'])->name('create');
        Route::post('/', [AdminEventController::class, 'store'])->name('store');
        Route::get('/{event}', [AdminEventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [AdminEventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [AdminEventController::class, 'update'])->name('update');
        Route::delete('/{event}', [AdminEventController::class, 'destroy'])->name('destroy');
        Route::post('/{event}/toggle-publish', [AdminEventController::class, 'togglePublish'])->name('toggle-publish');
        Route::post('/{event}/toggle-feature', [AdminEventController::class, 'toggleFeature'])->name('toggle-feature');
        Route::post('/registrations/{registration}/status', [AdminEventController::class, 'updateRegistrationStatus'])->name('registrations.status');
        Route::post('/registrations/{registration}/attendance', [AdminEventController::class, 'markAttendance'])->name('registrations.attendance');
        Route::get('/{event}/export-registrations', [AdminEventController::class, 'exportRegistrations'])->name('export-registrations');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportsController::class, 'index'])->name('index');
        Route::get('/alumni', [AdminReportsController::class, 'alumniReport'])->name('alumni');
        Route::get('/businesses', [AdminReportsController::class, 'businessReport'])->name('businesses');
        Route::get('/events', [AdminReportsController::class, 'eventsReport'])->name('events');
        Route::get('/export-alumni', [AdminReportsController::class, 'exportAlumni'])->name('export-alumni');
        Route::get('/system-stats', [AdminReportsController::class, 'systemStats'])->name('system-stats');
    });
    
    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-notification', [AdminSettingsController::class, 'testNotification'])->name('settings.test-notification');
    
    // Year Groups
    Route::resource('year-groups', AdminYearGroupController::class)->except(['show']);
    Route::patch('/year-groups/{yearGroup}/toggle-active', [AdminYearGroupController::class, 'toggleActive'])->name('year-groups.toggle-active');
    
    // Chapters
    Route::resource('chapters', AdminChapterController::class)->except(['show']);
    Route::get('/chapters/pending/list', [AdminChapterController::class, 'pending'])->name('chapters.pending');
    Route::patch('/chapters/{chapter}/approve', [AdminChapterController::class, 'approve'])->name('chapters.approve');
    Route::patch('/chapters/{chapter}/toggle-active', [AdminChapterController::class, 'toggleActive'])->name('chapters.toggle-active');
    
    // Broadcast Messages
    Route::get('/broadcast', [AdminBroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast/send', [AdminBroadcastController::class, 'send'])->name('broadcast.send');
    
    // Donations Management
    Route::prefix('donations')->name('donations.')->group(function () {
        Route::get('/', [DonationController::class, 'index'])->name('index');
        Route::get('/{donation}', [DonationController::class, 'show'])->name('show');
        Route::patch('/{donation}/status', [DonationController::class, 'updateStatus'])->name('update-status');
    });
});

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});