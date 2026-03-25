<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductService
{
    public function store(Request $request): Product
    {
        $validated = $request->validated();

        $product = $this->createProduct($validated);
        $this->attachCategories($validated, $product);
        $this->createVariants($validated, $product);
        $this->handleThumbnail($request, $product);

        return $product;
    }

    // ── Private helpers ────────────────────────────────

    private function createProduct(array $validated): Product
    {
        return Product::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'brand_id'    => $validated['brand_id'],
            'is_active'   => $validated['is_active'],
            'is_featured' => $validated['is_featured'],
            'tags'        => $validated['tags'] ?? [],
        ]);
    }

    private function attachCategories(array $validated, Product $product): void
    {
        // First category is treated as primary
        $syncData = collect($validated['category_ids'])
            ->mapWithKeys(fn ($id, $index) => [
                $id => ['is_primary' => $index === 0],
            ])
            ->all();

        $product->categories()->attach($syncData);
    }

    private function createVariants(array $validated, Product $product): void
    {
        foreach ($validated['variants_decoded'] as $index => $variant) {
            $product->variants()->create([
                'name'           => $variant['name'],
                'price'          => $variant['price'],
                'stock_quantity' => $variant['stock_quantity'] ?? 0,
                'is_active'      => $variant['is_active'] ?? true,
                'sort_order'     => $index,
            ]);
        }
    }

    private function handleThumbnail(Request $request, Product $product): void
    {
        if ($request->hasFile('thumbnail')) {
            $product->addMediaFromRequest('thumbnail')
                ->toMediaCollection('thumbnail');
        }
    }
}
