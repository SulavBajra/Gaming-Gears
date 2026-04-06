<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:100',
            'shipping_line1' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal' => 'nullable|string|max:20',
            'shipping_country' => 'nullable|string|size:2',
        ]);

        $user = $request->user();
        $cartItems = $user->cart->items()->get();

        if ($cartItems->count() === 0) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $total = (int) round($user->cartTotal() * 100);

        if ($total < 50) { // Stripe minimum is $0.50
            return response()->json(['message' => 'Order total is too low'], 422);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // CheckoutController.php
        $intent = PaymentIntent::create([
            'amount' => $total,
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'shipping' => [
                'name' => $request->shipping_name,
                'address' => [
                    'line1' => $request->shipping_line1,
                    'city' => $request->shipping_city,
                    'postal_code' => $request->shipping_postal,
                    'country' => $request->shipping_country ?? 'NP',
                ],
            ],
            'metadata' => [
                'user_id' => $request->user()->id,
            ],
        ]);

        return response()->json(['client_secret' => $intent->client_secret]);
    }
}
