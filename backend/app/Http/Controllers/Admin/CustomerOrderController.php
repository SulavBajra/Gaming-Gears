<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOrderResource;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\UpdateOrderStatus;
use App\Http\Requests\Admin\CustomerStatusUpdateRequest;
use App\Http\Resources\CustomerOrderViewResource;

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

        $orders = $query->latest()->paginate(6)->withQueryString();

        return Inertia::render('customers/Index', [
            'orders' => CustomerOrderResource::collection($orders),
            'filters' => $request->only(['search', 'order_status', 'payment_status']),
        ]);
    }

    public function edit(Order $order)
    {
        $order = $order->load(['orderStatus','paymentStatus']);
        return Inertia::render('customers/Edit', [
            'order' => new CustomerOrderViewResource($order),
        ]);
    }

    public function update(CustomerStatusUpdateRequest $request, Order $order)
    {
        $order->update($request->validated());
        return to_route('customers.edit', $order)->with('success', 'Order updated successfully');
    }
}
