{{--
    Shared Property Form Partial
    Included by: agent/properties/create.blade.php and agent/properties/edit.blade.php
    $property is null on create, an existing Property instance on edit.
    $categories, $locations, $amenities are passed from the controller in both cases.
--}}
@php
    $property = $property ?? null;
    $selectedAmenityIds = $property ? $property->amenities->pluck('id')->toArray() : old('amenities', []);
@endphp

<div class="row g-4">

    {{-- ============ BASIC INFO ============ --}}
    <div class="col-12">
        <div class="rep-card p-4">
            <h3 class="rep-h4 mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h3>

            <div class="mb-3">
                <label class="form-label rep-small">Property Title</label>
                <input type="text" name="title" value="{{ old('title', $property->title ?? '') }}" class="form-control rep-input @error('title') is-invalid @enderror" placeholder="e.g. 3BHK Luxury Apartment in Downtown" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label rep-small">Category</label>
                    <select name="category_id" class="form-select rep-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $property->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Listing Type</label>
                    <select name="listing_type" class="form-select rep-select @error('listing_type') is-invalid @enderror" required>
                        <option value="sale" {{ old('listing_type', $property->listing_type ?? '') === 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="rent" {{ old('listing_type', $property->listing_type ?? '') === 'rent' ? 'selected' : '' }}>For Rent</option>
                    </select>
                    @error('listing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Status</label>
                    <select name="status" class="form-select rep-select @error('status') is-invalid @enderror" required>
                        @foreach(['available' => 'Available', 'pending' => 'Pending', 'sold' => 'Sold', 'rented' => 'Rented'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $property->status ?? 'available') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-0">
                <label class="form-label rep-small">Description</label>
                <textarea name="description" rows="5" class="form-control rep-input @error('description') is-invalid @enderror" placeholder="Describe the property in detail..." required>{{ old('description', $property->description ?? '') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- ============ PRICING & SPECS ============ --}}
    <div class="col-12">
        <div class="rep-card p-4">
            <h3 class="rep-h4 mb-3"><i class="bi bi-rulers me-2"></i>Pricing &amp; Specifications</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label rep-small">Price (₹)</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $property->price ?? '') }}" class="form-control rep-input @error('price') is-invalid @enderror" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Area (sqft)</label>
                    <input type="number" step="0.01" min="0" name="area_sqft" value="{{ old('area_sqft', $property->area_sqft ?? '') }}" class="form-control rep-input @error('area_sqft') is-invalid @enderror" required>
                    @error('area_sqft')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Year Built</label>
                    <input type="number" min="1900" max="{{ date('Y') + 2 }}" name="year_built" value="{{ old('year_built', $property->year_built ?? '') }}" class="form-control rep-input @error('year_built') is-invalid @enderror">
                    @error('year_built')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Bedrooms</label>
                    <input type="number" min="0" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms ?? 0) }}" class="form-control rep-input @error('bedrooms') is-invalid @enderror">
                    @error('bedrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Bathrooms</label>
                    <input type="number" min="0" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms ?? 0) }}" class="form-control rep-input @error('bathrooms') is-invalid @enderror">
                    @error('bathrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label rep-small">Floors</label>
                    <input type="number" min="1" name="floors" value="{{ old('floors', $property->floors ?? 1) }}" class="form-control rep-input @error('floors') is-invalid @enderror">
                    @error('floors')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ============ LOCATION ============ --}}
    <div class="col-12">
        <div class="rep-card p-4">
            <h3 class="rep-h4 mb-3"><i class="bi bi-geo-alt me-2"></i>Location</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label rep-small">City / Region</label>
                    <select name="location_id" class="form-select rep-select @error('location_id') is-invalid @enderror" required>
                        <option value="">Select Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id', $property->location_id ?? '') == $location->id ? 'selected' : '' }}>{{ $location->city }}, {{ $location->state }}</option>
                        @endforeach
                    </select>
                    @error('location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label rep-small">Full Address</label>
                    <input type="text" name="address" value="{{ old('address', $property->address ?? '') }}" class="form-control rep-input @error('address') is-invalid @enderror" placeholder="Street, landmark, building name" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label rep-small">Latitude <span class="rep-text-muted">(optional)</span></label>
                    <input type="text" name="latitude" value="{{ old('latitude', $property->latitude ?? '') }}" class="form-control rep-input" placeholder="e.g. 18.5204">
                </div>
                <div class="col-md-6">
                    <label class="form-label rep-small">Longitude <span class="rep-text-muted">(optional)</span></label>
                    <input type="text" name="longitude" value="{{ old('longitude', $property->longitude ?? '') }}" class="form-control rep-input" placeholder="e.g. 73.8567">
                </div>
            </div>
        </div>
    </div>

    {{-- ============ AMENITIES ============ --}}
    <div class="col-12">
        <div class="rep-card p-4">
            <h3 class="rep-h4 mb-3"><i class="bi bi-stars me-2"></i>Amenities</h3>
            <div class="row g-2">
                @foreach($amenities as $amenity)
                    <div class="col-6 col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity{{ $amenity->id }}"
                                {{ in_array($amenity->id, $selectedAmenityIds) ? 'checked' : '' }}>
                            <label class="form-check-label rep-small" for="amenity{{ $amenity->id }}">
                                <i class="bi {{ $amenity->icon ?? 'bi-check2' }} me-1"></i>{{ $amenity->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============ MEDIA UPLOADS ============ --}}
    <div class="col-12">
        <div class="rep-card p-4">
            <h3 class="rep-h4 mb-3"><i class="bi bi-images me-2"></i>Media</h3>

            {{-- Cover image --}}
            <div class="mb-4">
                <label class="form-label rep-small">Cover Image {{ $property ? '' : '(required)' }}</label>
                <input type="file" name="cover_image" id="coverImageInput" accept="image/*" class="form-control rep-input @error('cover_image') is-invalid @enderror" {{ $property ? '' : 'required' }}>
                @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="mt-2" id="coverImagePreviewWrap">
                    @if($property && $property->cover_image)
                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="Cover" style="width:140px;height:100px;object-fit:cover;border-radius:var(--rep-radius-sm);">
                    @endif
                </div>
            </div>

            {{-- Gallery images --}}
            <div class="mb-4">
                <label class="form-label rep-small">Gallery Images <span class="rep-text-muted">(multiple, min 3 recommended)</span></label>
                <input type="file" name="gallery_images[]" id="galleryInput" accept="image/*" multiple class="form-control rep-input @error('gallery_images.*') is-invalid @enderror">
                @error('gallery_images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <div class="d-flex flex-wrap gap-2 mt-3" id="galleryPreviewWrap">
                    @if($property)
                        @foreach($property->galleries as $galleryImage)
                            <div class="position-relative">
                                <img src="{{ $galleryImage->thumbnail_url }}" style="width:90px;height:90px;object-fit:cover;border-radius:var(--rep-radius-sm);">
                                <button type="submit" form="deleteGallery{{ $galleryImage->id }}" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0" style="width:22px;height:22px;padding:0;line-height:1;" data-confirm="Remove this image?">&times;</button>
                            </div>
                            <form id="deleteGallery{{ $galleryImage->id }}" method="POST" action="{{ route('agent.properties.gallery.destroy', $galleryImage->id) }}" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="row g-3">
                {{-- Virtual tour video --}}
                <div class="col-md-6">
                    <label class="form-label rep-small">Virtual Tour Video <span class="rep-text-muted">(MP4, max 100MB — uploads in the background)</span></label>
                    <input type="file" id="videoInput" accept="video/mp4" class="form-control rep-input @error('virtual_tour_video_path') is-invalid @enderror" data-chunk-upload-url="{{ route('agent.properties.video-chunk') }}">
                    @error('virtual_tour_video_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-2" id="videoPreviewWrap">
                        @if($property && $property->virtual_tour_video)
                            <video src="{{ asset('storage/' . $property->virtual_tour_video) }}" style="width:100%;max-width:260px;border-radius:var(--rep-radius-sm);" controls></video>
                        @endif
                    </div>
                </div>

                {{-- Floor plan image --}}
                <div class="col-md-6">
                    <label class="form-label rep-small">Floor Plan Image <span class="rep-text-muted">(used by interactive canvas viewer)</span></label>
                    <input type="file" name="floor_plan_image" id="floorPlanInput" accept="image/*" class="form-control rep-input @error('floor_plan_image') is-invalid @enderror">
                    @error('floor_plan_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-2" id="floorPlanPreviewWrap">
                        @if($property && $property->floor_plan_image)
                            <img src="{{ asset('storage/' . $property->floor_plan_image) }}" style="width:140px;height:100px;object-fit:cover;border-radius:var(--rep-radius-sm);">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button type="submit" class="rep-btn rep-btn-primary rep-btn-lg">
            <i class="bi bi-check-circle"></i> {{ $property ? 'Update Property' : 'Publish Property' }}
        </button>
        <a href="{{ route('agent.properties.index') }}" class="rep-btn rep-btn-outline rep-btn-lg">Cancel</a>
    </div>
</div>
