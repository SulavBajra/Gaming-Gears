<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case PENDING = 1;
    case CONFIRMED = 2;
    case PROCESSING = 3;
    case SHIPPED = 4;
    case DELIVERED = 5;
    case CANCELLED = 6;
    case REFUNDED = 7;
}
