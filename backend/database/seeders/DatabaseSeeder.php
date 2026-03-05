<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Colorway;
use App\Models\Gender;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
        Gender::factory()->createMany([
            ['name' => 'Mens',   'slug' => 'mens'],
            ['name' => 'Womens', 'slug' => 'womens'],
            ['name' => 'Unisex', 'slug' => 'unisex'],
            ['name' => 'Kids',   'slug' => 'kids'],
        ]);

        // 2. Brands
        Brand::factory(8)->create();

        // 3. Categories
        Category::factory()->createMany([
            ['name' => 'Running',       'slug' => 'running',       'sort_order' => 1],
            ['name' => 'Basketball',    'slug' => 'basketball',    'sort_order' => 2],
            ['name' => 'Lifestyle',     'slug' => 'lifestyle',     'sort_order' => 3],
            ['name' => 'Skateboarding', 'slug' => 'skateboarding', 'sort_order' => 4],
        ]);

        // 4. Products
        Product::factory(20)
            ->recycle(Brand::all())
            ->recycle(Category::all())
            ->recycle(Gender::all())
            ->create();

        // 5. Colorways
        Product::all()->each(function ($product) {
            Colorway::factory(3)->for($product)->create();
        });

        // 6. Product Variants
        Colorway::all()->each(function ($colorway) {
            ProductVariant::factory(8)
                ->for($colorway->product)
                ->for($colorway)
                ->create();
        });
    }
}
