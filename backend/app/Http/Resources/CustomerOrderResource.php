<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'total' => $this->total,
            'customer_email' => $this->customer_email,
            'created_at' => $this->updated_at->format('Y-m-d'),
            'order_status' => $this->orderStatus?->name,
            'payment_status' => $this->paymentStatus?->name,
        ];
    }
}
