<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_status_id')->constrained('order_statuses')->restrictOnDelete();
            // pending, confirmed, processing, shipped, delivered, cancelled, refunded
            $table->foreignId('payment_status_id')->constrained('payment_statuses')->restrictOnDelete();
            // unpaid, paid, refunded, failed
            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('NPR');

            // Customer info (denormalized for order history integrity)
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();

            // Addresses (JSON snapshot)
            $table->json('shipping_address');
            // Add to orders table via new migration
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_status_id', 'payment_status_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
