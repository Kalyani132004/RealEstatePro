<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::withCount('properties')->orderBy('city')->paginate(15);

        return view('admin.locations.index', compact('locations'));
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        Location::create($request->validated());

        return back()->with('success', 'Location added successfully.');
    }

    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($request->validated());

        return back()->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return back()->with('success', 'Location deleted successfully.');
    }
}
