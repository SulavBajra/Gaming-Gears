<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'cart_item_id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'updated_at' => $this->updated_at,
            'item_total_price' => $this->quantity * (float) $this->unit_price,
            'product_name' => $this->whenLoaded('product', fn () => $this->product?->name),
            'product_variant_name' => $this->whenLoaded('productVariant', fn () => $this->productVariant?->name),
        ];
    }
}
