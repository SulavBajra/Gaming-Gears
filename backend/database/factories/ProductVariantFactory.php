<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Red Switch', 'Blue Switch', 'Brown Switch', 'Silver Switch', 'Yellow Switch']),
            'price' => fake()->randomElement([9500, 12000, 14500, 18000, 22500]),
            'stock' => fake()->numberBetween(5, 50),
        ];
    }
}
