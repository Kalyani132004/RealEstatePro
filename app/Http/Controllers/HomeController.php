<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('properties')->orderBy('name')->get();
        $locations = Location::orderBy('city')->get();

        $featuredProperties = Property::with(['category', 'location'])
            ->available()
            ->featured()
            ->latest()
            ->take(6)
            ->get();

        $recentProperties = Property::with(['category', 'location'])
            ->available()
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'properties' => Property::count(),
            'agents' => User::where('role', User::ROLE_AGENT)->count(),
            'clients' => User::where('role', User::ROLE_USER)->count(),
            'cities' => Location::count(),
        ];

        return view('home.index', compact(
            'categories',
            'locations',
            'featuredProperties',
            'recentProperties',
            'stats'
        ));
    }
}
