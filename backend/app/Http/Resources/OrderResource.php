<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'order_status' => $this->whenLoaded('orderStatus', $this->orderStatus->name),
            'payment_status' => $this->whenLoaded('paymentStatus', $this->paymentStatus->name),
            'total' => $this->total,
            'payment_method' => $this->payment_method,
            'shipping_address' => $this->shipping_address['line1'],
            'delivered_at' => $this->delivered_at?->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d'),

            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'id' => $i->id,
                'product_name' => $i->product_name,
                'quantity' => $i->quantity,
                'image' => $i->image,
                'product_snapshot' => $i->product_snapshot,
            ])),
        ];
    }
}
