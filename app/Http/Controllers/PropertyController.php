<?php

namespace App\Http\Controllers;

use App\Http\Requests\Property\PropertySearchRequest;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Property search results with advanced filters (sidebar form, Phase 8).
     * Query-builder filtering logic lives in Property::scopeFilter() (Phase 11,
     * hardened in Phase 15). Validated/sanitized filter input comes from
     * PropertySearchRequest (Phase 15) rather than raw $request->all().
     *
     * When the request comes from the progressive-enhancement AJAX filter form
     * (search-filters.js, Phase 15), only the lightweight results partial is
     * returned — not the full page — to keep filtering fast.
     */
    // public function search(PropertySearchRequest $request): View
    // {
    //     // $properties = Property::with(['category', 'location', 'agent.user'])
    //     //     ->available()
    //     //     ->filter($request->filters())
    //     //     ->paginate(12)
    //     //     ->withQueryString();

    //     $properties = Property::all();

    //     dd($properties);

    //     if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
    //         return view('properties.partials._results', compact('properties'));
    //     }

    //     $categories = Category::withCount('properties')->orderBy('name')->get();
    //     $locations = Location::orderBy('city')->get();
    //     $amenities = Amenity::orderBy('name')->get();

    //     return view('properties.search', compact('properties', 'categories', 'locations', 'amenities'));
    // }


    public function search(PropertySearchRequest $request): View
    {
        $properties = Property::with(['category', 'location', 'agent.user'])
            ->available()
            ->filter($request->filters())
            ->paginate(12)
            ->withQueryString();

        // ❌ He delete kar
        // dd($properties);

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('properties.partials._results', compact('properties'));
        }

        $categories = Category::withCount('properties')->orderBy('name')->get();
        $locations = Location::orderBy('city')->get();
        $amenities = Amenity::orderBy('name')->get();

        return view('properties.search', compact('properties', 'categories', 'locations', 'amenities'));
    }

    /**
     * Property details page. Route-model-bound by slug (Property::getRouteKeyName()).
     */
    public function show(Property $property): View
    {
        $property->load(['category', 'location', 'agent.user', 'galleries', 'amenities']);

        // Lightweight view tracking for Admin Reports (Phase 7/12).
        $property->increment('views_count');
        PropertyView::create([
            'property_id' => $property->id,
            'ip_address' => request()->ip(),
            'viewed_at' => now(),
        ]);

        $relatedProperties = Property::with(['category', 'location'])
            ->available()
            ->where('category_id', $property->category_id)
            ->where('id', '!=', $property->id)
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'relatedProperties'));
    }
}
