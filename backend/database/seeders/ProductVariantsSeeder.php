<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::with('brand')->get();

        foreach ($products as $product) {
            $this->createVariantsForProduct($product);
        }

        $this->command->info('Product variants seeded successfully!');
    }

    private function createVariantsForProduct(Product $product): void
    {
        // Define variant configurations based on product type
        $variants = $this->getVariantConfigurations($product);

        foreach ($variants as $variant) {
            ProductVariant::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                ],
                [
                    'stock_quantity' => $variant['stock_quantity'],
                    'price' => $variant['price'],
                    'low_stock_threshold' => $variant['low_stock_threshold'] ?? 5,
                    'weight' => $variant['weight'] ?? null,
                    'is_active' => true,
                    'sort_order' => $variant['sort_order'],
                ]
            );
        }
    }

    private function getVariantConfigurations(Product $product): array
    {
        $configurations = [];
        $productName = strtolower($product->name);

        // Keyboards
        if (str_contains($productName, 'keyboard')) {
            $basePrice = $this->getBasePrice($product->name);

            $configurations = [
                [
                    'name' => 'Standard - Black',
                    'stock_quantity' => rand(15, 40),
                    'price' => $basePrice,
                    'weight' => 1.2,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Standard - White',
                    'stock_quantity' => rand(10, 30),
                    'price' => $basePrice,
                    'weight' => 1.2,
                    'sort_order' => 2,
                ],
            ];

            // Add premium variant for high-end keyboards
            if (str_contains($productName, 'pro') || str_contains($productName, 'azoth') || str_contains($productName, 'apex')) {
                $configurations[] = [
                    'name' => 'Premium - With Wrist Rest',
                    'stock_quantity' => rand(5, 20),
                    'price' => $basePrice + 30,
                    'weight' => 1.5,
                    'sort_order' => 3,
                ];
            }
        }
        // Mice
        elseif (str_contains($productName, 'mouse') || str_contains($productName, 'g pro') || str_contains($productName, 'deathadder')) {
            $basePrice = $this->getBasePrice($product->name);

            $configurations = [
                [
                    'name' => 'Black',
                    'stock_quantity' => rand(20, 50),
                    'price' => $basePrice,
                    'weight' => 0.08,
                    'sort_order' => 1,
                ],
            ];

            // Add white variant for premium mice
            if (str_contains($productName, 'superlight') || str_contains($productName, 'deathadder') || str_contains($productName, 'harpe')) {
                $configurations[] = [
                    'name' => 'White',
                    'stock_quantity' => rand(10, 35),
                    'price' => $basePrice,
                    'weight' => 0.08,
                    'sort_order' => 2,
                ];
            }

            // Add grip tape variant
            $configurations[] = [
                'name' => 'With Grip Tape',
                'stock_quantity' => rand(5, 25),
                'price' => $basePrice + 10,
                'weight' => 0.09,
                'sort_order' => 3,
            ];
        }
        // Headsets
        elseif (str_contains($productName, 'headset') || str_contains($productName, 'g pro x') || str_contains($productName, 'arctis')) {
            $basePrice = $this->getBasePrice($product->name);

            $configurations = [
                [
                    'name' => 'Standard - Black',
                    'stock_quantity' => rand(20, 45),
                    'price' => $basePrice,
                    'weight' => 0.35,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Premium - With Stand',
                    'stock_quantity' => rand(5, 20),
                    'price' => $basePrice + 30,
                    'weight' => 0.4,
                    'sort_order' => 2,
                ],
            ];

            // Add white variant for some headsets
            if (str_contains($productName, 'arctis') || str_contains($productName, 'virtuoso')) {
                $configurations[] = [
                    'name' => 'White Edition',
                    'stock_quantity' => rand(8, 25),
                    'price' => $basePrice,
                    'weight' => 0.35,
                    'sort_order' => 3,
                ];
            }
        }

        return $configurations;
    }

    private function getBasePrice(string $productName): float
    {
        $productName = strtolower($productName);

        // Premium products ($200+)
        if (str_contains($productName, 'virtuoso') ||
            str_contains($productName, 'arctis nova pro') ||
            str_contains($productName, 'azoth')) {
            return rand(28000, 35000) / 100; // $280.00 - $350.00
        }

        // High-end products ($150-$200)
        if (str_contains($productName, 'pro x superlight') ||
            str_contains($productName, 'blackwidow v4') ||
            str_contains($productName, 'apex pro') ||
            str_contains($productName, 'g pro x wireless')) {
            return rand(15000, 20000) / 100; // $150.00 - $200.00
        }

        // Mid-range products ($100-$150)
        if (str_contains($productName, 'deathadder') ||
            str_contains($productName, 'k70') ||
            str_contains($productName, 'blackshark') ||
            str_contains($productName, 'harpe ace')) {
            return rand(10000, 15000) / 100; // $100.00 - $150.00
        }

        // Budget products ($50-$100)
        if (str_contains($productName, 'alloy origins') ||
            str_contains($productName, 'pulsefire') ||
            str_contains($productName, 'cloud iii')) {
            return rand(5000, 10000) / 100; // $50.00 - $100.00
        }

        // Default price
        return rand(8000, 12000) / 100; // $80.00 - $120.00
    }
}
