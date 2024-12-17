<?php

use App\Http\Controllers\DepartureController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;

Route::apiResource('departures', DepartureController::class);
Route::apiResource('advertisements', AdvertisementController::class);
Route::apiResource('announcements', AnnouncementController::class);
