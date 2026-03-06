<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Colorway>
 */
class ColorwayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['White', 'Black', 'Red', 'Blue', 'Green', 'Grey', 'Navy', 'Cream'];
        $color1 = fake()->randomElement($colors);
        $color2 = fake()->randomElement($colors);

        return [
            'product_id' => Product::factory(),
            'name' => "$color1/$color2",
            'colorway_code' => strtoupper("$color1-$color2"),
            'release_date' => fake()->dateTimeBetween('-2 years', '+6 months'),
            'is_limited_edition' => fake()->boolean(20),
        ];
    }

    public function limited(): static
    {
        return $this->state(['is_limited_edition' => true]);
    }

    public function upcoming(): static
    {
        return $this->state([
            'release_date' => fake()->dateTimeBetween('+1 day', '+6 months'),
        ]);
    }
}
