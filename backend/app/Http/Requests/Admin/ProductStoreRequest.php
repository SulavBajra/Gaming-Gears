<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'variants' => ['required', 'string'], // JSON string from FormData
            // Individual variant rules are validated after decoding — see prepareForValidation()
            'variants_decoded' => ['required', 'array', 'min:1'],
            'variants_decoded.*.name' => ['required', 'string', 'max:255'],
            'variants_decoded.*.price' => ['required', 'numeric', 'min:0'],
            'variants_decoded.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants_decoded.*.is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Decode the JSON variants string into an array so rules can validate each item
        $variants = [];
        if ($this->has('variants')) {
            $decoded = json_decode($this->input('variants'), true);
            if (is_array($decoded)) {
                $variants = $decoded;
            }
        }

        $this->merge([
            'is_active' => filter_var($this->input('is_active', true), FILTER_VALIDATE_BOOLEAN),
            'is_featured' => filter_var($this->input('is_featured', false), FILTER_VALIDATE_BOOLEAN),
            'variants_decoded' => $variants,
        ]);
    }

    public function messages(): array
    {
        return [
            'brand_id.exists' => 'The selected brand does not exist.',
            'category_ids.required' => 'At least one category is required.',
            'category_ids.*.exists' => 'One or more selected categories are invalid.',
            'variants_decoded.required' => 'At least one variant is required.',
            'variants_decoded.*.name.required' => 'Each variant must have a name.',
            'variants_decoded.*.price.required' => 'Each variant must have a price.',
            'variants_decoded.*.price.numeric' => 'Variant price must be a number.',
            'thumbnail.image' => 'Thumbnail must be an image file.',
            'thumbnail.max' => 'Thumbnail may not be larger than 2MB.',
        ];
    }
}
