<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        OrderStatus::insertOrIgnore([
            ['name' => 'Pending', 'code' => 'pending'],
            ['name' => 'Confirmed', 'code' => 'confirmed'],
            ['name' => 'Processing', 'code' => 'processing'],
            ['name' => 'Shipped', 'code' => 'shipped'],
            ['name' => 'Delivered', 'code' => 'delivered'],
            ['name' => 'Cancelled', 'code' => 'cancelled'],
            ['name' => 'Refunded', 'code' => 'refunded'],
        ]);

        PaymentStatus::insertOrIgnore([
            ['name' => 'Unpaid', 'code' => 'unpaid'],
            ['name' => 'Paid', 'code' => 'paid'],
            ['name' => 'Failed', 'code' => 'failed'],
            ['name' => 'Refunded', 'code' => 'refunded'],
        ]);
    }
}
