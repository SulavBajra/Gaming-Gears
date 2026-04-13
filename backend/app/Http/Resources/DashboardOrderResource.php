<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardOrderResource extends JsonResource
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
            'email' => $this->customer_email,
            'created_at' => $this->created_at->format('Y-m-d'),
            'order_status' => $this->orderStatus->name,
            'payment_status' => $this->paymentStatus->name,
        ];
    }
}
