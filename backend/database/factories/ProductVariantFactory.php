<?php

namespace Database\Factories;

use App\Models\Colorway;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomElement([89.99, 99.99, 119.99, 139.99, 159.99, 189.99]);

        return [
            'product_id' => Product::factory(),
            'colorway_id' => Colorway::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('??-####-??')),
            'size' => fake()->randomElement([6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 12, 13]),
            'width' => fake()->randomElement([null, null, null, 'D', '2E', '4E']),
            'price' => $price,
            'compare_at_price' => fake()->boolean(30) ? $price * 1.2 : null, // 30% on sale
            'stock_qty' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(['stock_qty' => 0]);
    }

    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            return ['compare_at_price' => $attributes['price'] * 1.25];
        });
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
