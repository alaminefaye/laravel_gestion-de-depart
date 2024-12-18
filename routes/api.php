<?php

use App\Http\Controllers\DepartureController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Departure;

Route::apiResource('departures', DepartureController::class);
Route::apiResource('advertisements', AdvertisementController::class);
Route::apiResource('announcements', AnnouncementController::class);

Route::get('/departures/{departure}/occupied-seats', function (Departure $departure) {
    $occupiedSeats = $departure->reservations()
        ->whereJsonLength('siege_numeros', '>', 0)
        ->get()
        ->pluck('siege_numeros')
        ->flatten()
        ->unique()
        ->values()
        ->toArray();

    return response()->json($occupiedSeats);
});
