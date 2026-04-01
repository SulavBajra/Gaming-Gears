<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'item_total_price' => $this->quantity * (float) $this->unit_price,
            'updated_at' => $this->updated_at,
            'product_name' => $this->whenLoaded('product', fn () => $this->product?->name),
            'product_variant_name' => $this->whenLoaded('productVariant', fn () => $this->productVariant?->name),
            'thumbnail' => $this->whenLoaded('product', fn () => $this->product?->getFirstMediaUrl('thumbnail')),
        ];
    }
}
