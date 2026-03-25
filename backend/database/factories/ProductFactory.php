<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Air Force 1', 'Ultra Boost', 'Chuck Taylor', 'Old Skool',
            'Classic Leather', 'Air Max 90', 'Stan Smith', '574',
        ]).' '.fake()->bothify('##??');

        return [
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'gender_id' => Gender::factory(),
            'name' => $name,
            'slug' => Str::slug($name.'-'.fake()->unique()->numberBetween(1000, 9999)),
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
