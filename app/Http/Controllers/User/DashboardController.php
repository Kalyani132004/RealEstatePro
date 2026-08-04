<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $savedCount = $user->savedProperties()->count();
        $enquiriesCount = $user->enquiries()->count();

        $filledFields = collect([$user->name, $user->email, $user->phone, $user->avatar])
            ->filter()
            ->count();
        $profileCompletion = (int) round(($filledFields / 4) * 100);

        $recentEnquiries = $user->enquiries()->with('property')->latest()->take(5)->get();
        $recentSaved = $user->savedProperties()->latest('saved_properties.created_at')->take(4)->get();

        return view('user.dashboard', compact(
            'savedCount',
            'enquiriesCount',
            'profileCompletion',
            'recentEnquiries',
            'recentSaved'
        ));
    }

    public function enquiries(Request $request): View
    {
        $enquiries = $request->user()
            ->enquiries()
            ->with('property')
            ->latest()
            ->paginate(10);

        return view('user.enquiries', compact('enquiries'));
    }
}
