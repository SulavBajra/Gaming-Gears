<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Keyboards' => [
                'Mechanical',
                'Membrane',
                'Wireless',
                '60%',
                'TKL',
            ],
            'Mice' => [
                'Wireless',
                'Wired',
                'MMO',
                'FPS',
                'Ergonomic',
            ],
            'Headsets' => [
                'Wireless',
                'Wired',
                'Surround Sound',
                'Noise Cancelling',
            ],
            'Accessories' => [
                'Mouse Pads',
                'Wrist Rests',
                'Cable Management',
                'Headset Stands',
            ],
            'Chairs' => [
                'Gaming Chairs',
                'Ergonomic Chairs',
            ],
            'Monitors' => [
                '144Hz',
                '240Hz',
                '4K',
                'Ultrawide',
            ],
        ];

        foreach ($categories as $parentName => $children) {

            // Create parent
            $parent = Category::firstOrCreate(
                [
                    'parent_id' => null,
                    'slug' => Str::slug($parentName),
                ],
                [
                    'name' => $parentName,
                    'sort_order' => 0,
                    'is_active' => true,
                ]
            );

            // Create children
            foreach ($children as $index => $childName) {
                Category::firstOrCreate(
                    [
                        'parent_id' => $parent->id,
                        'slug' => Str::slug($childName),
                    ],
                    [
                        'name' => $childName,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Categories seeded successfully!');
    }
}
