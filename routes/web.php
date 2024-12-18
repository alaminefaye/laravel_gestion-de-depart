<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DepartureController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes du Dashboard (protégées)
Route::middleware(['auth'])->group(function () {
    // Page d'accueil du dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
        // Page d'accueil du dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        
        // User Management Routes
        Route::resource('users', UserController::class);
        
        // Role Management Routes
        Route::resource('roles', RoleController::class);
        
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
        
        // Gestion des réservations
        Route::middleware(['auth'])->prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', [ReservationController::class, 'index'])->name('index');
            Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
            Route::get('/create', [ReservationController::class, 'create'])->name('create');
            Route::post('/', [ReservationController::class, 'store'])->name('store');
            Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
            Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
            Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
            Route::post('/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm');
            Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
        });
        
        // Gestion des annonces
        Route::resource('announcements', AnnouncementController::class);
        Route::patch('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggle'])
            ->name('announcements.toggle');

        // Paramètres
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::put('/settings/update', [SettingController::class, 'update'])->name('settings.update');
        Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
