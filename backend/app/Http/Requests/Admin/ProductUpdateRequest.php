<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
            'is_featured' => filter_var($this->is_featured, FILTER_VALIDATE_BOOLEAN),
            'variants' => json_decode($this->variants, true) ?? [],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
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
            'remove_thumbnail' => ['nullable', 'boolean'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_gallery_ids' => ['nullable', 'array'],
            'remove_gallery_ids.*' => ['integer'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'variants.*.name' => ['required', 'string', 'max:255'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0'],
            'variants.*.is_active' => ['boolean'],
        ];
    }
}
