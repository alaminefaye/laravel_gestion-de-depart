<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DepartureController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes du Dashboard (protégées)
Route::middleware(['auth'])->group(function () {
    // Page d'accueil du dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Gestion des départs
        Route::resource('departures', DepartureController::class);
        Route::put('/departures/{departure}/update-status', [DepartureController::class, 'updateStatus'])
            ->name('departures.update-status');
        
        // Gestion des statistiques
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
        
        // Gestion des bus
        Route::resource('buses', BusController::class);
        
        // Gestion des publicités
        Route::resource('advertisements', AdvertisementController::class);
        
        // Gestion des annonces
        Route::resource('announcements', AnnouncementController::class);
        Route::patch('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggle'])
            ->name('announcements.toggle');

        // Paramètres
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        Route::put('/settings/update-site', [DashboardController::class, 'updateSiteSettings'])
            ->name('settings.update-site');
        Route::put('/settings/update-notifications', [DashboardController::class, 'updateNotificationSettings'])
            ->name('settings.update-notifications');
        Route::put('/settings/update-maintenance', [DashboardController::class, 'updateMaintenanceSettings'])
            ->name('settings.update-maintenance');
        Route::put('/settings/update-booking', [DashboardController::class, 'updateBookingSettings'])
            ->name('settings.update-booking');
    });
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
