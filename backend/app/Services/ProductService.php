<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductUpdateRequest;
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

    public function update(ProductUpdateRequest $request, Product $product): Product
    {
        $validated = $request->validated();

        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'brand_id'    => $validated['brand_id'],
            'is_active'   => $validated['is_active'],
            'is_featured' => $validated['is_featured'],
            'tags'        => $validated['tags'] ?? [],
        ]);

        // Thumbnail
        if (!empty($validated['remove_thumbnail'])) {
            $product->clearMediaCollection('thumbnail');
        }
        if ($request->hasFile('thumbnail')) {
            $product->addMediaFromRequest('thumbnail')
                    ->toMediaCollection('thumbnail');
        }

        // Gallery
        if (!empty($validated['remove_gallery_ids'])) {
            $product->media()
                    ->whereIn('id', $validated['remove_gallery_ids'])
                    ->delete();
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $product->addMedia($image)->toMediaCollection('gallery');
            }
        }

        // Categories
        $this->attachCategories($validated, $product);

        // Variants — update existing, create new
        $this->syncVariants($validated['variants'], $product);

        return $product;
    }

    private function syncVariants(array $variants, Product $product): void
    {
        $incomingIds = collect($variants)->pluck('id')->filter()->values();

        // Delete variants removed on the frontend
        $product->variants()
                ->whereNotIn('id', $incomingIds)
                ->delete();

        foreach ($variants as $index => $variantData) {
            $payload = [
                'name'           => $variantData['name'],
                'price'          => $variantData['price'],
                'stock_quantity' => $variantData['stock_quantity'],
                'is_active'      => $variantData['is_active'] ?? true,
                'sort_order'     => $index,
            ];

            if (!empty($variantData['id'])) {
                // Update existing
                $product->variants()
                        ->where('id', $variantData['id'])
                        ->update($payload);
            } else {
                // Create new
                $product->variants()->create($payload);
            }
        }
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
        $syncData = collect($validated['category_ids'])->mapWithKeys(fn($id) => [
            $id => ['is_primary' => true],
        ]);

        // sync() removes old, attaches new — safe for both store and update
        $product->categories()->sync($syncData);
    }

    private function createVariants(array $validated, Product $product): void
    {
        foreach ($validated['variants'] as $index => $variant) {
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
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $product->addMedia($image)->toMediaCollection('gallery');
            }
        }
    }
}
