<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $userId = $intent->metadata->user_id ?? null;

            if (!$userId) {
                Log::error('Stripe webhook: missing user_id in metadata', [
                    'intent' => $intent->id,
                    'metadata' => $intent->metadata,
                ]);
                return response()->json(['error' => 'missing user_id'], 200);
            }

            try {
                OrderService::createFromIntent((int) $userId, $intent);
            } catch (\Exception $e) {
                Log::error('Stripe webhook: order creation failed', [
                    'intent'  => $intent->id,
                    'user_id' => $userId,
                    'error'   => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]);
                return response()->json(['error' => $e->getMessage()], 200);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
