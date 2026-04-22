<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderViewResource extends JsonResource
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
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'total' => $this->total,
            'shipping_address' => [
                'city' => $this->shipping_address['city'] ?? null,
                'line1' => $this->shipping_address['line1'] ?? null,
                'line2' => $this->shipping_address['line2'] ?? null,
            ],
            'payment_method' => $this->payment_method,
            'shipped_at' => $this->shipped_at?->format('Y-m-d'),
            'delivered_at' => $this->delivered_at?->format('Y-m-d'),
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'order_status' => $this->whenLoaded('orderStatus', function () {
                return [
                    'id' => $this->orderStatus->id,
                    'name' => $this->orderStatus->name,
                ];
            }),
            'payment_status' => $this->whenLoaded('paymentStatus', function () {
                return [
                    'id' => $this->paymentStatus->id,
                    'name' => $this->paymentStatus->name,
                ];
            }),
        ];
    }
}
