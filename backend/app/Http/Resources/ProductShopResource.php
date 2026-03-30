<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShopResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description ?? '',
            'tags' => $this->tags ?? [],
            'price' => (float) ($this->variants_min_price ?? 0),

            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(fn ($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                ]);
            }),

            'brand' => $this->whenLoaded('brand', function () {
                return [
                    'id' => $this->brand?->id,
                    'name' => $this->brand?->name,
                    'slug' => $this->brand?->slug,
                ];
            }),

            'thumbnail' => $this->getFirstMediaUrl('thumbnail'),
            'gallery' => $this->getMedia('gallery')->map(fn ($m) => $m->getUrl()),
            'variants' => $this->whenLoaded(
                'variants',
                fn () => $this->activeVariants->map(fn ($v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'price' => (float) $v->price,
                    'stock_quantity' => $v->stock_quantity,
                ])
            ),
        ];
    }
}
