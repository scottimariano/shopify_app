<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body_html' => 'nullable|string',
            'vendor' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
        ];
    }

    /**
     * Get the custom error messages for the validator.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'The product title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title cannot be longer than 255 characters.',
            'body_html.string' => 'The product description must be a string.',
            'vendor.required' => 'The vendor name is required.',
            'vendor.string' => 'The vendor name must be a string.',
            'vendor.max' => 'The vendor name cannot be longer than 255 characters.',
            'product_type.required' => 'The product type is required.',
            'product_type.string' => 'The product type must be a string.',
            'product_type.max' => 'The product type cannot be longer than 255 characters.',
        ];
    }
}
