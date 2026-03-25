<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        // Brand names relevant to a keyboard/mouse/headset store
        $knownBrands = [
            'Logitech G',
            'Razer',
            'Corsair',
            'SteelSeries',
            'HyperX',
            'Glorious',
            'Keychron',
            'Ducky',
            'ASUS ROG',
            'Cooler Master',
            'Sennheiser',
            'EPOS',
            'Audio-Technica',
            'Redragon',
            'Zowie',
        ];

        $name = $this->faker->unique()->randomElement($knownBrands);

        // Add a suffix sometimes to avoid collisions if seeding many
        if ($this->faker->boolean(35)) {
            $name .= ' ' . $this->faker->randomElement(['Pro', 'Gaming', 'Studio', 'Gear', 'Works']);
        }

        return [
            'name' => $name,

            // Spatie Sluggable can create this, but we must ensure uniqueness during seeding.
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 999999),

            'description' => $this->faker->optional(0.75)->paragraphs(2, true),

            // Either null or a plausible website
            'website' => $this->faker->optional(0.65)->url(),

            // Most brands active
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
