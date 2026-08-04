<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Location;
use App\Models\Property;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $monthlyListings = $this->monthlyListingsLast12Months();

        $listingsByType = [
            'labels' => ['Sale', 'Rent'],
            'data' => [
                Property::where('listing_type', Property::TYPE_SALE)->count(),
                Property::where('listing_type', Property::TYPE_RENT)->count(),
            ],
        ];

        $topAgents = Agent::with('user')
            ->withCount('properties')
            ->orderByDesc('properties_count')
            ->take(5)
            ->get();

        $topLocations = Location::withCount('properties')
            ->orderByDesc('properties_count')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'monthlyListings',
            'listingsByType',
            'topAgents',
            'topLocations'
        ));
    }

    private function monthlyListingsLast12Months(): array
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = Property::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
