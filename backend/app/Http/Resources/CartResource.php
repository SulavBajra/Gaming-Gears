<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'expires_at' => $this->expires_at,
            'total_items' => $this->items->sum('quantity'),
            'total_price' => (float) $this->items->sum(fn ($item) => $item->quantity * (float) $item->unit_price),
            'items' => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
