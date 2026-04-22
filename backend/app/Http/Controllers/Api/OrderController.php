<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $orders = Order::with(['items', 'orderStatus', 'paymentStatus'])
            ->where('user_id', $user->id)
            ->paginate(4);

        return OrderResource::collection($orders);
    }
}
