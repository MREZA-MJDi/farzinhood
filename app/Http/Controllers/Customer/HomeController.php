<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $user->loadCount([
            'orders',
            'wishlists',
            'addresses',
        ]);

        $recentOrders = $user->orders()
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', [
            'recentOrders' => $recentOrders,
            'orderCount' => (int) $user->orders_count,
            'wishlistCount' => (int) $user->wishlists_count,
            'addressCount' => (int) $user->addresses_count,
        ]);
    }
}
