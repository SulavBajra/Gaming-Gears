<?php

namespace App\Actions;

use App\Models\Order;
use App\Http\Requests\Admin\CustomerStatusUpdateRequest;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\Log;

class CustomerOrderStatusUpdate
{
    public function execute(Order $order, CustomerStatusUpdateRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($order, $validated) {

            $this->updateOrderStatus($order, $validated['order_status_id']);
            $this->updatePaymentStatus($order, $validated['payment_status_id']);
        });

        return $order->refresh();
    }

    private function updateOrderStatus(Order $order, int $status)
    {
        if ($status == OrderStatusEnum::SHIPPED->value) {
            $order->update(['order_status_id' => OrderStatusEnum::SHIPPED,
            'shipped_at' => now(), 'delivered_at' => null]);
            return;
        }
        if ($status == OrderStatusEnum::DELIVERED->value) {
            $order->update(['order_status_id' => OrderStatusEnum::DELIVERED,
            'delivered_at' => now()]);
            return;
        }
        if ($status == OrderStatusEnum::CANCELLED->value) {
            $order->update(['order_status_id' => OrderStatusEnum::CANCELLED,
            'cancelled_at' => now(), 'delivered_at' => null]);
            return;
        }

        $order->update(['order_status_id' => $status]);
        Log::info('Order status updated', ['order_status_id' => $status, 'delivered_at' => $order->delivered_at]);
    }

    private function updatePaymentStatus(Order $order, int $status)
    {
        if ($status == PaymentStatusEnum::PAID->value) {
            $order->update(['payment_status_id' => PaymentStatusEnum::PAID->value, 'paid_at' => now()]);
            return;
        }

        if ($status == PaymentStatusEnum::UNPAID->value) {
            $order->update(['payment_status_id' => PaymentStatusEnum::UNPAID->value, 'paid_at' => null]);
            return;
        }
    }
}
