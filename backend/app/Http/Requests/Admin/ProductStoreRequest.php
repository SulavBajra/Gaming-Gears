<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'gender_id' => 'required|exists:genders,id',
            'is_active' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048',
            'colorway_images' => 'nullable|array',
            'colorway_images.*' => 'array',
            'colorway_images.*.*' => 'image|max:2048',
        ];
    }
}
