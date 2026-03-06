<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logos = [
            'nike' => public_path('images/brands/nike.png'),
            'adidas' => public_path('images/brands/adidas.png'),
            'puma' => public_path('images/brands/puma.png'),
            // slug => path
        ];

        foreach ($logos as $slug => $path) {
            $brand = Brand::where('slug', $slug)->first();

            if ($brand && file_exists($path)) {
                $brand->clearMediaCollection('logo');
                $brand->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('logo');
            }
        }
    }
}
