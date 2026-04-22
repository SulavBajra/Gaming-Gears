<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case UNPAID = 1;
    case PAID = 2;
    case FAILED = 3;
    case REFUNDED = 4;
}
