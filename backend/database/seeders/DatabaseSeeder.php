<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use App\Models\PaymentStatus;
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
            BrandsSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        OrderStatus::insert([
            ['name' => 'Pending', 'code' => 'pending'],
            ['name' => 'Confirmed', 'code' => 'confirmed'],
            ['name' => 'Processing', 'code' => 'processing'],
            ['name' => 'Shipped', 'code' => 'shipped'],
            ['name' => 'Delivered', 'code' => 'delivered'],
            ['name' => 'Cancelled', 'code' => 'cancelled'],
            ['name' => 'Refunded', 'code' => 'refunded'],
        ]);

        PaymentStatus::insert([
            ['name' => 'Unpaid', 'code' => 'unpaid'],
            ['name' => 'Paid', 'code' => 'paid'],
            ['name' => 'Failed', 'code' => 'failed'],
            ['name' => 'Refunded', 'code' => 'refunded'],
        ]);
    }
}
