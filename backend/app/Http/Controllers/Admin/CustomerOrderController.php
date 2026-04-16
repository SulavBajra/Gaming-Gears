<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOrderResource;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\UpdateOrderStatus;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderStatus:id,name', 'paymentStatus:id,name']);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                    ->orWhere('customer_email', 'like', "%{$request->search}%");
            });
        }

        if ($request->order_status) {
            $query->whereHas('orderStatus', function ($q) use ($request) {
                $q->where('name', $request->order_status);
            });
        }

        if ($request->payment_status) {
            $query->whereHas('paymentStatus', function ($q) use ($request) {
                $q->where('name', $request->payment_status);
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('customers/Index', [
            'orders' => CustomerOrderResource::collection($orders),
            'filters' => $request->only(['search', 'order_status', 'payment_status']),
        ]);
    }

    public function updateStatus(Request $request, Order $order, UpdateOrderStatus $action)
    {
        $request->validate([
            'order_status' => 'required|exists:order_statuses,id',
        ]);

        $statusId = OrderStatus::query()->where('name', $request->order_status)->value('id');

        $action->execute($order, (int) $statusId);

        return back();
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|exists:payment_statuses,id',
        ]);
        return back();
    }
}
