<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $agent = $request->user()->agent;

        $totalProperties = $agent->properties()->count();
        $activeListings = $agent->properties()->where('status', Property::STATUS_AVAILABLE)->count();
        $totalEnquiries = $agent->enquiries()->count();
        $totalViews = $agent->properties()->sum('views_count');

        $recentProperties = $agent->properties()->latest()->take(5)->get();
        $recentEnquiries = $agent->enquiries()->latest()->take(5)->get();

        return view('agent.dashboard', compact(
            'totalProperties',
            'activeListings',
            'totalEnquiries',
            'totalViews',
            'recentProperties',
            'recentEnquiries'
        ));
    }
}
