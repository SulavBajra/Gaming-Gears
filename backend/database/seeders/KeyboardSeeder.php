<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class KeyboardSeeder extends Seeder
{
    public function run(): void
    {
        $logitech = Brand::firstOrCreate(['slug' => 'logitech'], ['name' => 'Logitech']);
        $razer = Brand::firstOrCreate(['slug' => 'razer'], ['name' => 'Razer']);
        $steelseries = Brand::firstOrCreate(['slug' => 'steelseries'], ['name' => 'SteelSeries']);
        $corsair = Brand::firstOrCreate(['slug' => 'corsair'], ['name' => 'Corsair']);
        $keychron = Brand::firstOrCreate(['slug' => 'keychron'], ['name' => 'Keychron']);

        $keyboards = Category::firstOrCreate(['slug' => 'keyboards'], ['name' => 'Keyboards']);

        // ── Seed specific hero products ───────────────────────

        $heroProducts = [
            [
                'name' => 'Logitech G Pro X Keyboard',
                'slug' => 'logitech-g-pro-x-keyboard',
                'description' => 'Tournament-grade tenkeyless keyboard with swappable GX switches and LIGHTSYNC RGB, built for esports professionals.',
                'brand_id' => $logitech->id,
                'tags' => ['gaming', 'pro', 'tenkeyless'],
                'variants' => [
                    ['name' => 'Red Switch',  'price' => 15000, 'stock' => 16],
                    ['name' => 'Blue Switch', 'price' => 14500, 'stock' => 31],
                    ['name' => 'Clicky Switch', 'price' => 15500, 'stock' => 10],
                ],
            ],
            [
                'name' => 'Razer BlackWidow V4 Pro',
                'slug' => 'razer-blackwidow-v4-pro',
                'description' => 'Full-size wireless mechanical keyboard with Razer Yellow linear switches, Chroma RGB, and dedicated media controls.',
                'price' => 22500,
                'brand_id' => $razer->id,
                'tags' => ['gaming', 'rgb', 'wireless'],
                'variants' => [
                    ['name' => 'Yellow Switch (Linear)',  'price' => 22500, 'stock' => 20],
                    ['name' => 'Green Switch (Clicky)',   'price' => 23000, 'stock' => 12],
                ],
            ],
            [
                'name' => 'Keychron Q1 Pro',
                'slug' => 'keychron-q1-pro',
                'description' => 'Premium QMK/VIA wireless custom mechanical keyboard with gasket mount, aluminum body, and hot-swappable switches.',
                'price' => 18000,
                'brand_id' => $keychron->id,
                'tags' => ['mechanical', 'pro', 'wireless'],
                'variants' => [
                    ['name' => 'Gateron G Pro Red',   'price' => 18000, 'stock' => 25],
                    ['name' => 'Gateron G Pro Brown',  'price' => 18000, 'stock' => 18],
                    ['name' => 'Barebone (No Switch)', 'price' => 15500, 'stock' => 8],
                ],
            ],
            [
                'name' => 'Corsair K70 RGB MK.2',
                'slug' => 'corsair-k70-rgb-mk2',
                'description' => 'Full-size gaming keyboard with aircraft-grade aluminum frame, Cherry MX switches, and per-key RGB backlighting.',
                'price' => 12000,
                'brand_id' => $corsair->id,
                'tags' => ['gaming', 'rgb', 'mechanical'],
                'variants' => [
                    ['name' => 'Cherry MX Red',   'price' => 12000, 'stock' => 30],
                    ['name' => 'Cherry MX Blue',  'price' => 12500, 'stock' => 22],
                    ['name' => 'Cherry MX Silent', 'price' => 13000, 'stock' => 14],
                ],
            ],
            [
                'name' => 'SteelSeries Apex Pro TKL',
                'slug' => 'steelseries-apex-pro-tkl',
                'description' => 'World\'s first keyboard with adjustable actuation OmniPoint 2.0 magnetic switches — tenkeyless form factor.',
                'price' => 28000,
                'brand_id' => $steelseries->id,
                'tags' => ['gaming', 'pro', 'tenkeyless'],
                'variants' => [
                    ['name' => 'Wired',    'price' => 28000, 'stock' => 10],
                    ['name' => 'Wireless', 'price' => 32000, 'stock' => 7],
                ],
            ],
        ];

        foreach ($heroProducts as $data) {
            $variants = $data['variants'];
            $tagNames = $data['tags'];
            unset($data['variants'], $data['tags']);

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['is_active' => true]),
            );

            // Sync category
            $product->categories()->syncWithoutDetaching([$keyboards->id]);

            // Sync tags
            $tagIds = collect($tagNames)->map(fn ($t) => $tags[$t])->filter();
            $product->tags()->syncWithoutDetaching($tagIds);

            // Sync variants
            foreach ($variants as $variant) {
                ProductVariant::updateOrCreate(
                    ['product_id' => $product->id, 'name' => $variant['name']],
                    ['price' => $variant['price'], 'stock' => $variant['stock']],
                );
            }
        }

        // ── Random filler products (uses ProductFactory) ──────

        Product::factory(10)->create(['is_active' => true])->each(function (Product $product) use ($keyboards, $tags) {
            $product->categories()->syncWithoutDetaching([$keyboards->id]);

            $randomTags = $tags->keys()->random(rand(1, 3))->map(fn ($t) => $tags[$t]);
            $product->tags()->syncWithoutDetaching($randomTags);

            $switches = ['Red Switch', 'Blue Switch', 'Brown Switch', 'Silver Switch'];
            foreach (array_slice($switches, 0, rand(2, 3)) as $sw) {
                ProductVariant::factory()->create([
                    'product_id' => $product->id,
                    'name' => $sw,
                ]);
            }
        });
    }
}
