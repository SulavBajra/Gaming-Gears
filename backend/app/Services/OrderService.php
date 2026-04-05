<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    public static function createFromIntent(int $userId, object $intent): Order
    {
        // Idempotency guard — Stripe retries webhooks on failure
        $existing = Order::where('stripe_payment_intent_id', $intent->id)->first();
        if ($existing) {
            Log::info('Duplicate webhook ignored', ['payment_intent' => $intent->id]);

            return $existing;
        }

        $user = User::with(['address:id,user_id,phone'])->where('id', $userId)->first();
        $orderStatusId = OrderStatus::where('code', 'confirmed')->value('id');
        $paymentStatusId = PaymentStatus::where('code', 'paid')->value('id');

        if (! $orderStatusId || ! $paymentStatusId) {
            throw new \Exception('Required order/payment status codes not seeded.');
        }

        return DB::transaction(function () use ($user, $intent, $orderStatusId, $paymentStatusId) {
            $cartItems = $user->cart->items()
                ->with(['product.media', 'productVariant'])
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $cartItems->sum(fn ($item) => $item->unit_price * $item->quantity);
            // 0 for now but will be changed into shipping cost
            $total = $subtotal + 0;

            // 1. Create the order
            $order = Order::create([
                'order_number' => static::generateOrderNumber($intent->id),
                'user_id' => $user->id,
                'order_status_id' => $orderStatusId,
                'payment_status_id' => $paymentStatusId,
                'subtotal' => $subtotal,
                'total' => $total,
                'currency' => strtoupper($intent->currency),
                'stripe_payment_intent_id' => $intent->id,
                // Customer snapshot
                'payment_method' => 'Stripe',
                'customer_email' => $user->email,
                'customer_name' => $user->name,
                'customer_phone' => $user->customer->phone ?? ' ',
                // Address snapshots — pull from user profile or cart
                'shipping_address' => static::resolveShippingAddress($user, $intent),
                'paid_at' => now(),
            ]);

            $items = [];
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $variant = $cartItem->productVariant;
                $productImage = $product->getFirstMediaUrl('thumbnail') ?: null;

                $items[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant?->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'image' => $productImage,
                    'product_snapshot' => json_encode(
                        static::buildProductSnapshot($product, $variant)
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $products[] = [
                    'product_variant_id' => $variant?->id,
                    'quantity' => $cartItem->quantity,
                ];
            }

            DB::table('order_items')->insert($items);
            foreach ($products as $product) {
                $variantId = $product['product_variant_id'];
                $quantity = $product['quantity'];
                    DB::table('product_variants')
                        ->where('id', $variantId)
                        ->decrement('stock_quantity', $quantity);
            }
            // 3. Clear the cart
            $user->cart->items()->delete();


            DB::afterCommit(function () use ($user, $order) {
                Mail::to($user->email)->queue(
                    new OrderConfirmation($order->load('items'))
                );
            });


            Log::info('Order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_intent' => $intent->id,
                'total' => $total,
                'items_count' => count($items),
            ]);

            return $order;
        });
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    protected static function generateOrderNumber(string $intentId): string
    {
        // GG-<year>-<last 8 chars of intent id> e.g. GG-2025-A1B2C3D4
        return 'GG-'.date('Y').'-'.strtoupper(substr($intentId, -8));
    }

    protected static function buildProductSnapshot(
        $product,
        $variant = null
    ): array {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'thumbnail' => $product->getFirstMediaUrl('thumbnail'),
            'variant' => $variant ? [
                'id' => $variant->id,
                'name' => $variant->name,
            ] : null,
        ];
    }

    protected static function resolveShippingAddress(User $user, object $intent): array
    {
        // Stripe can carry shipping details if you pass them during PaymentIntent creation.
        // Fall back to user profile address otherwise.
        if (! empty($intent->shipping)) {
            $s = $intent->shipping;

            return [
                'name' => $s->name,
                'line1' => $s->address->line1,
                'line2' => $s->address->line2 ?? null,
                'city' => $s->address->city,
                'state' => $s->address->state ?? null,
                'country' => $s->address->country,
                'phone' => $s->phone ?? null,
            ];
        }

        // Fall back to user's saved address
        return $user->shipping_address ?? [
            'name' => $user->name,
            'line1' => '',
            'city' => '',
        ];
    }

    protected static function resolveBillingAddress(User $user, object $intent): array
    {
        // Stripe billing_details lives on the PaymentMethod, not the intent directly.
        // If you expand the payment_method when creating the intent you can read it here.
        // For now, mirror shipping address — extend when you add a billing form.
        return static::resolveShippingAddress($user, $intent);
    }
}
