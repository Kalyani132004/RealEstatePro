<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'avatar' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'avatar.file' => 'Please select a valid file.',
            'avatar.mimes' => 'Only JPG, JPEG, PNG and WEBP images are allowed.',
            'avatar.max' => 'Image size must be less than 5 MB.',
        ];
    }
}