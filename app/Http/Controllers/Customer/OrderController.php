<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load([
            'items.product:id,name,slug',
            'latestPayment',
            'statusHistories' => fn ($query) => $query
                ->latest()
                ->with('changedBy:id,name'),
        ]);

        return view('customer.orders.show', compact('order'));
    }
}
