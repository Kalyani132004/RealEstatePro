<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::where('role', User::ROLE_USER)->count();
        $totalAgents = User::where('role', User::ROLE_AGENT)->count();
        $totalProperties = Property::count();
        $totalEnquiries = Enquiry::count();

        $recentUsers = User::latest()->take(5)->get();
        $recentProperties = Property::with('agent.user')->latest()->take(5)->get();

        $propertiesByCategory = [
            'labels' => Category::withCount('properties')->get()->pluck('name'),
            'data' => Category::withCount('properties')->get()->pluck('properties_count'),
        ];

        $enquiriesTrend = $this->enquiriesLast7Days();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAgents',
            'totalProperties',
            'totalEnquiries',
            'recentUsers',
            'recentProperties',
            'propertiesByCategory',
            'enquiriesTrend'
        ));
    }

    private function enquiriesLast7Days(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = Enquiry::whereDate('created_at', $date->toDateString())->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
