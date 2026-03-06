<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($brandId)],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }
}
