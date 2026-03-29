<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Red Switch', 'Blue Switch', 'Brown Switch', 'Silver Switch', 'Yellow Switch']),
            'price' => $this->faker->randomElement([9500, 12000, 14500, 18000, 22500]),
            'stock' => $this->faker->numberBetween(5, 50),
        ];
    }
}
