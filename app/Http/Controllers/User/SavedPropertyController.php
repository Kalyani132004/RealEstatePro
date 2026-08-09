<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedPropertyController extends Controller
{
    public function index(Request $request): View
    {
        $savedProperties = $request->user()
            ->savedProperties()
            ->with(['category', 'location'])
            ->latest('saved_properties.created_at')
            ->paginate(9);

        return view('user.saved-properties', compact('savedProperties'));
    }

    /**
     * AJAX toggle, called from the heart icon on every property card
    
     */
    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
        ]);

        $user = $request->user();
        $property = Property::findOrFail($validated['property_id']);

        if ($user->savedProperties()->where('property_id', $property->id)->exists()) {
            $user->savedProperties()->detach($property->id);
            $saved = false;
        } else {
            $user->savedProperties()->attach($property->id);
            $saved = true;
        }

        return response()->json(['saved' => $saved]);
    }
}
