<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Logitech',
                'slug' => 'logitech',
                'description' => 'Logitech is a Swiss company known for high-quality computer peripherals and gaming gear.',
                'website' => 'https://www.logitech.com',
                'is_active' => true,
            ],
            [
                'name' => 'Razer',
                'slug' => 'razer',
                'description' => 'Razer is a global leader in gaming hardware, software, and systems.',
                'website' => 'https://www.razer.com',
                'is_active' => true,
            ],
            [
                'name' => 'Corsair',
                'slug' => 'corsair',
                'description' => 'Corsair is a leading provider of high-performance PC components and peripherals.',
                'website' => 'https://www.corsair.com',
                'is_active' => true,
            ],
            [
                'name' => 'SteelSeries',
                'slug' => 'steelseries',
                'description' => 'SteelSeries is a Danish company specializing in gaming peripherals and accessories.',
                'website' => 'https://steelseries.com',
                'is_active' => true,
            ],
            [
                'name' => 'HyperX',
                'slug' => 'hyperx',
                'description' => 'HyperX is a gaming peripherals brand known for high-performance memory, headsets, and keyboards.',
                'website' => 'https://www.hyperx.com',
                'is_active' => true,
            ],
            [
                'name' => 'ASUS ROG',
                'slug' => 'asus-rog',
                'description' => 'ASUS Republic of Gamers (ROG) is a premium gaming brand for high-performance hardware.',
                'website' => 'https://rog.asus.com',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }

        $this->command->info('Brands seeded successfully!');
    }
}
