<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ownership check (agent owns this property, or is admin) is handled by
        // PropertyPolicy::update() via $this->authorize() in the controller.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'listing_type' => ['required', 'in:sale,rent'],
            'status' => ['required', 'in:available,pending,sold,rented'],
            'description' => ['required', 'string', 'min:20'],

            'price' => ['required', 'numeric', 'min:0'],
            'area_sqft' => ['required', 'numeric', 'min:1'],
            'year_built' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 2)],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'floors' => ['nullable', 'integer', 'min:1', 'max:200'],

            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],

            // Cover image is NOT required on update — an existing one may already be set.
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_images' => ['nullable', 'array', 'max:15'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'virtual_tour_video_path' => ['nullable', 'string'],
            'floor_plan_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.min' => 'The description should be at least 20 characters so buyers have enough context.',
            'gallery_images.max' => 'You can upload a maximum of 15 gallery images.',
        ];
    }
}
