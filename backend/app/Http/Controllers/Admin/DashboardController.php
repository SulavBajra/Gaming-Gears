<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardOrderResource;
use App\Models\Order;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $paidOrders = Order::query()->where('payment_status_id', 2);

        $week_total = (clone $paidOrders)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total');

        $month_total = (clone $paidOrders)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $year_total = (clone $paidOrders)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $pending_orders = Order::query()
            ->whereIn('order_status_id', [1, 2, 3, 4])
            ->count();

        $completed_order = Order::query()
            ->where('order_status_id', 5)
            ->count();

        $recentOrders = Order::with([
            'orderStatus:id,name',
            'paymentStatus:id,name',
        ])
        ->select('id', 'order_number', 'customer_email', 'total', 'created_at', 'order_status_id', 'payment_status_id')
        ->latest()->limit(10)->get();

        return Inertia::render('Dashboard', [
            'pending_orders' => $pending_orders,
            'week_total' => $week_total,
            'month_total' => $month_total,
            'year_total' => $year_total,
            'completed_order' => $completed_order,
            'recentOrders' => DashboardOrderResource::collection($recentOrders)->toArray(request()),
        ]);
    }

    public function recentOrders()
    {
        $orders = Order::with([
            'orderStatus:id,name',
            'paymentStatus:id,name',
        ])
        ->select('id', 'order_number', 'customer_email', 'total', 'created_at', 'order_status_id', 'payment_status_id')
        ->latest()->take(10)->get();

        return Inertia::render('Dashboard', [
            'recentOrders' => DashboardOrderResource::collection($orders),
        ]);
    }
}
