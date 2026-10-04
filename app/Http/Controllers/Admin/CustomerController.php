<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerIndexRequest;
use App\Http\Requests\Admin\CustomerStatusRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(CustomerIndexRequest $request)
    {
        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where(
                    'is_active',
                    $request->input('status') === 'active'
                );
            })
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }
    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        $customer->loadCount('orders');
        $customer->loadSum('orders', 'total');

        $orders = $customer->orders()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.customers.show', compact(
            'customer',
            'orders'
        ));
    }
    public function toggleStatus(
        CustomerStatusRequest $request,
        User $customer
    ): RedirectResponse {
        abort_unless(
            $customer->role === 'customer',
            404
        );

        $customer->update([
            'is_active' => $request->boolean('status'),
        ]);

        return back()->with(
            'success',
            $request->boolean('status')
                ? 'Customer activated successfully.'
                : 'Customer deactivated successfully.'
        );
    }
}
