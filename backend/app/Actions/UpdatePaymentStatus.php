<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;

class UpdateOrderStatus
{
    public function execute(Order $order, int $statusId)
    {
        $data = [
            'order_status' => $statusId,
        ];

        if ($statusId === OrderStatus::SHIPPED) {
            $data['shipped_at'] = now();
        }

        if ($statusId === OrderStatus::DELIVERED) {
            $data['delivered_at'] = now();
        }

        if ($statusId === OrderStatus::CANCELLED) {
            $data['cancelled_at'] = now();
        }

        $order->update($data);
    }
}
