<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class PropertySearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public search page, open to guests
    }

    /**
     * All fields are optional — an empty filter set simply returns everything.
     * 'sometimes' + 'nullable' means a present-but-empty field (e.g. a blank
     * <select>) never throws a validation error; it's just treated as "no filter".
     */
    public function rules(): array
    {
        return [
            'keyword' => ['sometimes', 'nullable', 'string', 'max:150'],
            'category' => ['sometimes', 'nullable', 'string', 'exists:categories,slug'],
            'location' => ['sometimes', 'nullable', 'integer', 'exists:locations,id'],
            'listing_type' => ['sometimes', 'nullable', 'in:sale,rent'],
            'min_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'nullable', 'numeric', 'min:0', 'gte:min_price'],
            'bedrooms' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:10'],
            'bathrooms' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:10'],
            'amenities' => ['sometimes', 'nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
            'featured' => ['sometimes', 'nullable', 'boolean'],
            'sort' => ['sometimes', 'nullable', 'in:latest,price_low,price_high'],
            'page' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'max_price.gte' => 'Maximum price must be greater than or equal to the minimum price.',
        ];
    }

    /**
     * Only the filter keys the model's scopeFilter() knows how to use —
     * strips out anything unexpected before it ever reaches the query builder.
     */
    public function filters(): array
    {
        return $this->safe()->only([
            'keyword', 'category', 'location', 'listing_type',
            'min_price', 'max_price', 'bedrooms', 'bathrooms',
            'amenities', 'featured', 'sort',
        ]);
    }
}
