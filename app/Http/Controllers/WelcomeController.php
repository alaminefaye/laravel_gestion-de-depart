<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Announcement;
use App\Models\Departure;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('is_active', true)->get();
        
        // Récupérer les départs avec les relations bus
        $departures = Departure::with('bus')
            ->whereDate('scheduled_time', '>=', now())
            ->orderBy('scheduled_time')
            ->take(5)
            ->get();
        
        $advertisements = Advertisement::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        return view('welcome', compact('announcements', 'departures', 'advertisements'));
    }
}
