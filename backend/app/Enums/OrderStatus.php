<?php

namespace App\Enums;

enum OrderStatus
{
    const PENDING = 1;

    const CONFIRMED = 2;

    const PROCESSING = 3;

    const SHIPPED = 4;

    const DELIVERED = 5;

    const CANCELLED = 6;

    const REFUNDED = 7;
}
