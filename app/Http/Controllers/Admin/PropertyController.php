<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $properties = Property::query()
            ->with(['agent.user', 'category'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    public function toggleFeature(Property $property): RedirectResponse
    {
        $property->update(['is_featured' => ! $property->is_featured]);

        return back()->with('success', 'Featured status updated.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete(); // soft delete

        return back()->with('success', 'Property deleted successfully.');
    }
}
