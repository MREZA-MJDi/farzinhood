<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'orders' => $user->orders()->count(),
            'wishlists' => $user->wishlists()->count(),
            'addresses' => $user->addresses()->count(),
        ];

        $recentOrders = $user->orders()
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'stats',
            'recentOrders',
        ));
    }
}
