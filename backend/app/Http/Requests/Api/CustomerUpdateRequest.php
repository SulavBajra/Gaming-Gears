<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim((string) $this->first_name),
            'last_name' => trim((string) $this->last_name),
            'company' => $this->company ? trim($this->company) : null,
            'address_line_1' => trim((string) $this->address_line_1),
            'address_line_2' => $this->address_line_2
                ? trim($this->address_line_2)
                : null,
            'city' => trim((string) $this->city),
            'state' => trim((string) $this->state),
            'gender' => $this->gender
                ? strtolower(trim($this->gender))
                : null,
        ]);
    }

    public function rules(): array
    {
        $customerId = $this->route('customer');

        return [
            'phone' => [
                'required',
                'digits:10',
                Rule::unique('customers', 'phone')->ignore($customerId),
            ],

            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
                'after:' . now()->subYears(120)->toDateString(),
            ],

            'gender' => [
                'nullable',
                Rule::in(['male', 'female', 'other']),
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\'-]+$/u',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\'-]+$/u',
            ],

            'company' => ['nullable', 'string', 'max:255'],

            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],

            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'phone.unique' => 'This phone number is already in use.',
            'date_of_birth.before_or_equal' => 'Customer must be at least 18 years old.',
            'date_of_birth.after' => 'Invalid date of birth.',
            'first_name.regex' => 'First name contains invalid characters.',
            'last_name.regex' => 'Last name contains invalid characters.',
        ];
    }
}
