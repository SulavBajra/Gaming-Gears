<?php

namespace App\Services;

use App\Http\Requests\Api\CartRequest;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function addToCart(CartRequest $request): Cart
    {
        $variant = ProductVariant::where('product_id', $request->product_id)
            ->where('id', $request->product_variant_id)
            ->first();

        if (! $variant || ! $variant->inStock()) {
            throw ValidationException::withMessages([
                'product_variant_id' => ['Product not available'],
            ]);
        }

        if ($variant->stock_quantity < $request->quantity) {
            throw ValidationException::withMessages([
                'quantity' => ['Not enough stock'],
            ]);
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $cartItem = $cart->items()->firstOrNew([
            'product_id' => $request->product_id,
            'product_variant_id' => $request->product_variant_id,
        ]);
        $cartItem->quantity += $request->quantity;
        $cartItem->unit_price = $variant->price;
        $cartItem->save();

        return $cart;
    }

    public function getCart($userId)
    {
        $cart = Cart::with('items.product', 'items.productVariant')
            ->where('user_id', $userId)
            ->first();

        if (! $cart) {
            return collect();
        }

        return $cart;
    }
}
