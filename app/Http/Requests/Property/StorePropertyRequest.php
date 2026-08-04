<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ownership is not relevant on create (there's no existing property yet);
        // PropertyPolicy::create() already gates the route via the controller.
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

            'cover_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_images' => ['nullable', 'array', 'max:15'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'virtual_tour_video_path' => ['nullable', 'string'],
            'floor_plan_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'cover_image.required' => 'Please upload a cover image for the property.',
            'description.min' => 'The description should be at least 20 characters so buyers have enough context.',
            'gallery_images.max' => 'You can upload a maximum of 15 gallery images.',
            'virtual_tour_video.mimes' => 'The virtual tour must be an MP4 video file.',
        ];
    }
}
