<?php

namespace App\Enums;

enum PaymentStatus
{
    const UNPAID = 1;
    const PAID = 2;
    const FAILED = 3;
    const REFUNDED = 4;
}
