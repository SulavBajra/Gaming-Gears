<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductService
{
    public function store(Request $request): Product
    {
        $product = $this->createProduct($request);
        $this->handleThumbnail($request, $product);
        $this->createColorways($request, $product);

        return $product;
    }

    // ── Private helpers ────────────────────────────────

    private function createProduct(Request $request): Product
    {
        return Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'gender_id' => $request->gender_id,
            'is_active' => $request->boolean('is_active', true),
        ]);
    }

    private function handleThumbnail(Request $request, Product $product): void
    {
        if ($request->hasFile('thumbnail')) {
            $product->addMediaFromRequest('thumbnail')
                ->toMediaCollection('thumbnail');
        }
    }

    private function createColorways(Request $request, Product $product): void
    {
        $colorwaysMeta = json_decode($request->input('colorways'), true) ?? [];

        foreach ($colorwaysMeta as $ci => $data) {
            $colorway = $product->colorways()->create([
                'name' => $data['name'],
                'colorway_code' => $data['colorway_code'] ?? null,
                'release_date' => $data['release_date'] ?: null,
                'is_limited_edition' => $data['is_limited_edition'] ?? false,
            ]);

            $this->handleColorwayImages($request, $colorway, $ci);
            $this->createVariants($colorway, $product->id, $data['variants'] ?? []);
        }
    }

    private function handleColorwayImages(Request $request, $colorway, int $ci): void
    {
        if (! $request->hasFile("colorway_images.{$ci}")) {
            return;
        }

        $files = $request->file("colorway_images.{$ci}");

        // First image goes to 'primary', all images go to 'images'
        foreach ($files as $index => $image) {
            $colorway->addMedia($image)
                ->toMediaCollection('images');

            if ($index === 0) {
                // Re-add as primary (singleFile will replace on next upload)
                $colorway->addMedia(
                    $request->file("colorway_images.{$ci}")[0]
                )->toMediaCollection('primary');
            }
        }
    }

    private function createVariants($colorway, int $productId, array $variants): void
    {
        foreach ($variants as $data) {
            $colorway->variants()->create([
                'product_id' => $productId,
                'sku' => $data['sku'],
                'size' => $data['size'],
                'width' => $data['width'] ?: null,
                'price' => $data['price'],
                'compare_at_price' => $data['compare_at_price'] ?: null,
                'stock_qty' => $data['stock_qty'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
            ]);
        }
    }
}
