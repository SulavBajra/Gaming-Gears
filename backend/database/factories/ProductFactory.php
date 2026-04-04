<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['keyboard', 'mouse', 'headset']);
        $name = $this->generateProductName($type);

        return [
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 999),
            'description' => fake()->paragraph(3),
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
            'tags' => fake()->randomElements(['gaming', 'RGB', 'mechanical', 'wireless', 'wired'], fake()->numberBetween(1, 3)),
        ];
    }

    public function keyboard(): static
    {
        return $this->state(fn () => ['name' => $this->generateProductName('keyboard')]);
    }

    public function mouse(): static
    {
        return $this->state(fn () => ['name' => $this->generateProductName('mouse')]);
    }

    public function headset(): static
    {
        return $this->state(fn () => ['name' => $this->generateProductName('headset')]);
    }

    /** Attach a random category from the correct type */
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            // Match the categories you actually have
            $categorySlugs = match (true) {
                str_contains(strtolower($product->name), 'keyboard') => ['keyboards'],
                str_contains(strtolower($product->name), 'mouse') => ['mice'],
                str_contains(strtolower($product->name), 'headset') => ['headsets'],
                default => ['accessories'],
            };

            // Get random category from the matched ones (or add random variation)
            $categories = Category::whereIn('slug', $categorySlugs)->get();

            // Optional: Add random variation (keyboards-1, keyboards-2, etc.)
            if ($categories->isEmpty() && str_contains(strtolower($product->name), 'keyboard')) {
                $categories = Category::where('slug', 'like', 'keyboards%')->get();
            } elseif ($categories->isEmpty() && str_contains(strtolower($product->name), 'mouse')) {
                $categories = Category::where('slug', 'like', 'mice%')->get();
            } elseif ($categories->isEmpty() && str_contains(strtolower($product->name), 'headset')) {
                $categories = Category::where('slug', 'like', 'headsets%')->get();
            }

            $product->categories()->sync($categories->pluck('id'));

            // Your variant creation code remains the same...
            $variantsCount = rand(1, 3);
            for ($i = 1; $i <= $variantsCount; $i++) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => "Variant $i",
                    'stock_quantity' => rand(5, 100),
                    'price' => fake()->randomFloat(2, 50, 500),
                    'low_stock_threshold' => 5,
                    'weight' => fake()->randomFloat(2, 0.5, 5),
                    'is_active' => true,
                    'sort_order' => $i,
                ]);
            }
        });
    }

    private function generateProductName(string $type): string
    {
        $brands = Brand::pluck('name')->toArray();
        $lines = ['G Pro X', 'BlackWidow V4', 'Apex Pro', 'K70 RGB', 'Alloy Origins', 'One 3', 'Q1 Pro', 'Strix Scope'];

        $brand = fake()->randomElement($brands);
        $line = fake()->randomElement($lines);

        return match ($type) {
            'keyboard' => "$brand $line Keyboard",
            'mouse' => "$brand $line Mouse",
            'headset' => "$brand $line Headset",
            default => "$brand $line Product",
        };
    }
}
