<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use App\Services\ImageUploadService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PropertyController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ImageUploadService $images)
    {
    }

    public function index(Request $request): View
    {
        $properties = $request->user()->agent
            ->properties()
            ->with(['category', 'location'])
            ->latest()
            ->paginate(10);

        return view('agent.properties.index', compact('properties'));
    }

    public function create(): View
    {
        return view('agent.properties.create', $this->formData());
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $this->authorize('create', Property::class);

        $validated = $request->validated();
        $videoPath = $validated['virtual_tour_video_path'] ?? null;
        unset($validated['virtual_tour_video_path']);

        $agent = $request->user()->agent;

        $property = new Property($validated);
        $property->agent_id = $agent->id;

        // Cover + floor plan: resized (max 1920px / 2400px) and re-encoded to
        // WebP via ImageUploadService (Phase 16). Video was already uploaded
        // in chunks via VideoUploadController before this form was submitted
        // (Phase 17) — we just persist the resulting storage path here.
        if ($request->hasFile('cover_image')) {
            $property->cover_image = $this->images->store($request->file('cover_image'), 'properties/covers', maxWidth: 1920, quality: 82);
        }
        if ($videoPath) {
            $property->virtual_tour_video = $videoPath;
        }
        if ($request->hasFile('floor_plan_image')) {
            // Floor plans need to stay sharp when the Phase 8 canvas viewer zooms in,
            // so they get a larger max width and higher quality than regular photos.
            $property->floor_plan_image = $this->images->store($request->file('floor_plan_image'), 'properties/floor-plans', maxWidth: 2400, quality: 90);
        }

        $property->save();

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $stored = $this->images->storeWithThumbnail($file, 'properties/gallery');

                $property->galleries()->create([
                    'image_path' => $stored['path'],
                    'thumbnail_path' => $stored['thumbnail_path'],
                    'sort_order' => $index,
                ]);
            }
        }

        $property->amenities()->sync($validated['amenities'] ?? []);

        return redirect()->route('agent.properties.index')->with('success', 'Property published successfully.');
    }

    public function edit(Property $property): View
    {
        $this->authorize('update', $property);

        $property->load('galleries', 'amenities');

        return view('agent.properties.edit', array_merge(['property' => $property], $this->formData()));
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $this->images->delete($property->cover_image);
            $validated['cover_image'] = $this->images->store($request->file('cover_image'), 'properties/covers', maxWidth: 1920, quality: 82);
        }
        // Same pre-uploaded-path pattern as store() above (Phase 17).
        if (! empty($validated['virtual_tour_video_path'])) {
            if ($property->virtual_tour_video) {
                Storage::disk('public')->delete($property->virtual_tour_video);
            }
            $validated['virtual_tour_video'] = $validated['virtual_tour_video_path'];
        }
        unset($validated['virtual_tour_video_path']);
        if ($request->hasFile('floor_plan_image')) {
            $this->images->delete($property->floor_plan_image);
            $validated['floor_plan_image'] = $this->images->store($request->file('floor_plan_image'), 'properties/floor-plans', maxWidth: 2400, quality: 90);
        }

        $property->update($validated);

        if ($request->hasFile('gallery_images')) {
            $startOrder = $property->galleries()->max('sort_order') + 1;
            foreach ($request->file('gallery_images') as $index => $file) {
                $stored = $this->images->storeWithThumbnail($file, 'properties/gallery');

                $property->galleries()->create([
                    'image_path' => $stored['path'],
                    'thumbnail_path' => $stored['thumbnail_path'],
                    'sort_order' => $startOrder + $index,
                ]);
            }
        }

        $property->amenities()->sync($validated['amenities'] ?? []);

        return redirect()->route('agent.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $this->authorize('delete', $property);

        $property->delete(); // soft delete

        return back()->with('success', 'Property deleted successfully.');
    }

    /**
     * Shared dropdown/checkbox data for the create/edit form partial.
     */
    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('city')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
        ];
    }
}
