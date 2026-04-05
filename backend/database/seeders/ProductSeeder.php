<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $keyboards = Category::where('slug', 'keyboards')->first();
        $mice = Category::where('slug', 'mice')->first();
        $headsets = Category::where('slug', 'headsets')->first();

        // Brands
        $logitech = Brand::where('slug', 'logitech')->first();
        $razer = Brand::where('slug', 'razer')->first();
        $steelseries = Brand::where('slug', 'steelseries')->first();

        $products = [
            [
                'name' => 'Logitech G Pro X Keyboard',
                'brand' => $logitech,
                'description' => fake()->sentence(12),
                'category' => $keyboards,
                'tags' => ['gaming', 'pro'],
                'is_active' => true,
                'variants' => [
                    ['name' => 'Red Switch', 'price' => 15000, 'stock_quantity' => 100],
                    ['name' => 'Blue Switch', 'price' => 14500, 'stock_quantity' => 100],
                ],
            ],
            [
                'name' => 'Razer DeathAdder V3',
                'brand' => $razer,
                'description' => fake()->sentence(12),
                'category' => $mice,
                'tags' => ['gaming', 'pro'],
                'is_active' => true,
                'variants' => [
                    ['name' => 'Standard', 'price' => 8500, 'stock_quantity' => 100],
                    ['name' => 'Wireless', 'price' => 12000, 'stock_quantity' => 100],
                ],
            ],
            [
                'name' => 'SteelSeries Arctis 7',
                'brand' => $steelseries,
                'description' => fake()->sentence(12),
                'category' => $headsets,
                'tags' => ['gaming', 'pro'],
                'is_active' => true,
                'variants' => [
                    ['name' => 'Black', 'price' => 18000, 'stock_quantity' => 100],
                    ['name' => 'White', 'price' => 18500, 'stock_quantity' => 100],
                ],
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'brand_id' => $data['brand']?->id,
                'description' => fake()->sentence(12),
                'is_active' => true,
                'is_featured' => fake()->boolean(30),
                'tags' => ['gaming', 'pro'],
            ]);

            // Category
            if ($data['category']) {
                $product->categories()->attach([
                    $data['category']->id => ['is_primary' => true],
                ]);
            }

            // Variants
            foreach ($data['variants'] as $index => $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'stock_quantity' => rand(5, 50),
                    'low_stock_threshold' => 5,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        $this->command->info('Products with brands & variants seeded!');
    }
}
