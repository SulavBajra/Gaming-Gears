<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'customer' => [
                'id' => $this->customer->id,
                'phone' => $this->customer->phone,
                'date_of_birth' => $this->customer->date_of_birth->format('Y-m-d'),
                'gender' => $this->customer->gender,
            ],
            'address' => [
                'id' => $this->address->id,
                'first_name' => $this->address->first_name,
                'last_name' => $this->address->last_name,
                'company' => $this->address->company,
                'address_line_1' => $this->address->address_line_1,
                'address_line_2' => $this->address->address_line_2,
                'city' => $this->address->city,
                'state' => $this->address->state,
            ],
        ];
    }
}
