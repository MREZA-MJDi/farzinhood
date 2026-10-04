<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderIndexRequest;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(OrderIndexRequest $request): View
    {
        $orders = Order::query()
            ->with([
                'user:id,name,email',
            ])
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('shipping_full_name', 'like', "%{$search}%")
                        ->orWhere('shipping_phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where(
                    'status',
                    $request->validated('status')
                );
            })
            ->when($request->filled('payment_status'), function ($query) use ($request) {
                $query->where(
                    'payment_status',
                    $request->validated('payment_status')
                );
            })
            ->latest()
            ->paginate(20);

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order): View
    {
        $order->load([
            'user:id,name,email',
            'items.product:id,name,slug',
            'latestPayment',
            'statusHistories.changedBy:id,name',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function updateStatus(
        OrderStatusRequest $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validated();

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            return back();
        }

        $order->update([
            'status' => $newStatus,
        ]);

        $order->statusHistories()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'changed_by' => $request->user()->id,
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with(
            'success',
            'وضعیت سفارش با موفقیت بروزرسانی شد.'
        );
    }
}
