<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DashboardChartRequest;
use App\Models\ContactMessage;
use App\Models\InventoryMovement;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Date Ranges
        |--------------------------------------------------------------------------
        */

        $today = now()->startOfDay();
        $tomorrow = now()->copy()->addDay()->startOfDay();

        $monthStart = now()->copy()->startOfMonth();
        $nextMonth = now()->copy()->addMonth()->startOfMonth();

        $yearStart = now()->copy()->startOfYear();
        $nextYear = now()->copy()->addYear()->startOfYear();


        /*
        |--------------------------------------------------------------------------
        | Sales KPIs
        |--------------------------------------------------------------------------
        */

        $todaySales = $this->paidOrders()
            ->where('created_at', '>=', $today)
            ->where('created_at', '<', $tomorrow)
            ->sum('total');

        $monthSales = $this->paidOrders()
            ->where('created_at', '>=', $monthStart)
            ->where('created_at', '<', $nextMonth)
            ->sum('total');

        $yearSales = $this->paidOrders()
            ->where('created_at', '>=', $yearStart)
            ->where('created_at', '<', $nextYear)
            ->sum('total');


        /*
        |--------------------------------------------------------------------------
        | Sales Growth
        |--------------------------------------------------------------------------
        */

        $previousMonthStart = now()
            ->copy()
            ->subMonth()
            ->startOfMonth();

        $previousMonthEnd = now()
            ->copy()
            ->subMonth()
            ->endOfMonth();

        $previousMonthSales = $this->paidOrders()
            ->whereBetween('created_at', [
                $previousMonthStart,
                $previousMonthEnd,
            ])
            ->sum('total');

        $monthGrowth = $previousMonthSales > 0
            ? (($monthSales - $previousMonthSales) / $previousMonthSales) * 100
            : ($monthSales > 0 ? 100 : 0);


        /*
        |--------------------------------------------------------------------------
        | Order Statistics
        |--------------------------------------------------------------------------
        */

        $ordersCount = Order::query()->count();

        $todayOrders = Order::query()
            ->where('created_at', '>=', $today)
            ->where('created_at', '<', $tomorrow)
            ->count();

        $pendingOrders = Order::query()
            ->where('status', 'pending')
            ->count();

        $processingOrders = Order::query()
            ->where('status', 'processing')
            ->count();

        $shippedOrders = Order::query()
            ->where('status', 'shipped')
            ->count();

        $deliveredOrders = Order::query()
            ->where('status', 'delivered')
            ->count();

        $cancelledOrders = Order::query()
            ->where('status', 'cancelled')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Customer Statistics
        |--------------------------------------------------------------------------
        */

        $customersCount = User::query()
            ->where('role', 'customer')
            ->count();

        $activeCustomersCount = User::query()
            ->where('role', 'customer')
            ->where('is_active', true)
            ->count();

        $newCustomersThisMonth = User::query()
            ->where('role', 'customer')
            ->whereBetween('created_at', [
                $monthStart,
                now(),
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Product / Inventory Statistics
        |--------------------------------------------------------------------------
        */

        $productsCount = Product::query()->count();

        $activeProductsCount = Product::query()
            ->where('is_active', true)
            ->count();

        $outOfStockCount = Product::query()
            ->where('is_active', true)
            ->where('stock', 0)
            ->count();

        $lowStockCount = Product::query()
            ->where('is_active', true)
            ->whereBetween('stock', [1, 5])
            ->count();

        $lowStockProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Review Statistics
        |--------------------------------------------------------------------------
        */

        $reviewsCount = Review::query()->count();

        $pendingReviews = Review::query()
            ->where('status', 'pending')
            ->count();

        $approvedReviews = Review::query()
            ->where('status', 'approved')
            ->count();

        $recentReviews = Review::query()
            ->with(['product', 'user'])
            ->latest()
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Contact Message Statistics
        |--------------------------------------------------------------------------
        */

        $unreadMessages = ContactMessage::query()
            ->where('status', 'new')
            ->count();

        $totalMessages = ContactMessage::query()->count();

        $recentMessages = ContactMessage::query()
            ->latest()
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Newsletter
        |--------------------------------------------------------------------------
        */

        $newsletterSubscribers = NewsletterSubscriber::query()
            ->where('is_active', true)
            ->count();

        $newsletterTotal = NewsletterSubscriber::query()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::query()
            ->with('user')
            ->withCount('items')
            ->latest()
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Inventory Movements
        |--------------------------------------------------------------------------
        */

        $recentInventoryMovements = InventoryMovement::query()
            ->with(['product', 'user'])
            ->latest()
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Best Selling Products
        |--------------------------------------------------------------------------
        */

        $bestSellingProducts = OrderItem::query()
            ->selectRaw('
                product_id,
                MAX(product_name) as product_name,
                SUM(quantity) as total_quantity,
                SUM(total) as total_sales
            ')
            ->whereHas('order', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Today's Sales Summary
        |--------------------------------------------------------------------------
        */

        $todayOrderCount = $this->paidOrders()
            ->where('created_at', '>=', $today)
            ->where('created_at', '<', $tomorrow)
            ->count();

        $todayAverageOrderValue = $todayOrderCount > 0
            ? $todaySales / $todayOrderCount
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Month Sales Summary
        |--------------------------------------------------------------------------
        */

        $monthOrderCount = $this->paidOrders()
            ->where('created_at', '>=', $monthStart)
            ->where('created_at', '<', $nextMonth)
            ->count();

        $monthAverageOrderValue = $monthOrderCount > 0
            ? $monthSales / $monthOrderCount
            : 0;


        /*
        |--------------------------------------------------------------------------
        | 7 Day Sales Overview
        |--------------------------------------------------------------------------
        */

        $salesOverview = $this->dailySalesData(7);


        /*
        |--------------------------------------------------------------------------
        | Order Status Overview
        |--------------------------------------------------------------------------
        */

        /*
|--------------------------------------------------------------------------
| Order Status Overview
|--------------------------------------------------------------------------
*/

        $orderStatusOverview = [
            'pending' => $pendingOrders,
            'processing' => $processingOrders,
            'shipped' => $shippedOrders,
            'delivered' => $deliveredOrders,
            'cancelled' => $cancelledOrders,
        ];
        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'todaySales',
            'monthSales',
            'yearSales',
            'monthGrowth',

            'ordersCount',
            'todayOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',

            'customersCount',
            'activeCustomersCount',
            'newCustomersThisMonth',

            'productsCount',
            'activeProductsCount',
            'outOfStockCount',
            'lowStockCount',
            'lowStockProducts',

            'reviewsCount',
            'pendingReviews',
            'approvedReviews',
            'recentReviews',

            'unreadMessages',
            'totalMessages',
            'recentMessages',

            'newsletterSubscribers',
            'newsletterTotal',

            'recentOrders',
            'recentInventoryMovements',
            'bestSellingProducts',

            'todayOrderCount',
            'todayAverageOrderValue',
            'monthOrderCount',
            'monthAverageOrderValue',

            'salesOverview',
            'orderStatusOverview'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Dashboard Chart API
    |--------------------------------------------------------------------------
    */

    public function chart(
        DashboardChartRequest $request
    ): JsonResponse {
        $range = $request->validated('range');

        $data = match ($range) {
            'daily' => $this->dailySalesData(30),

            'monthly' => $this->monthlySalesData(),

            'yearly' => $this->yearlySalesData(),

            default => [
                'labels' => [],
                'data' => [],
            ],
        };

        return response()->json($data);
    }


    /*
    |--------------------------------------------------------------------------
    | Paid Orders Query
    |--------------------------------------------------------------------------
    */

    private function paidOrders()
    {
        return Order::query()
            ->where('payment_status', 'paid');
    }


    /*
    |--------------------------------------------------------------------------
    | Daily Sales
    |--------------------------------------------------------------------------
    */

    private function dailySalesData(int $days): array
    {
        $start = now()
            ->copy()
            ->subDays($days - 1)
            ->startOfDay();

        $end = now()
            ->copy()
            ->endOfDay();

        $rows = $this->paidOrders()
            ->selectRaw('DATE(created_at) as sale_date, SUM(total) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $labels = [];
        $data = [];

        $period = CarbonPeriod::create(
            $start->copy()->startOfDay(),
            '1 day',
            $end->copy()->startOfDay()
        );

        foreach ($period as $date) {
            $key = $date->format('Y-m-d');

            $labels[] = $date
                ->locale('fa')
                ->translatedFormat('j F');

            $data[] = (int) ($rows[$key]->total ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Monthly Sales - Current Year
    |--------------------------------------------------------------------------
    */

    private function monthlySalesData(): array
    {
        $year = now()->year;

        $rows = $this->paidOrders()
            ->selectRaw('MONTH(created_at) as month_number, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('month_number')
            ->get()
            ->keyBy('month_number');

        $labels = [];
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(
                $year,
                $month,
                1
            );

            $labels[] = $date
                ->locale('fa')
                ->translatedFormat('F');

            $data[] = (int) ($rows[$month]->total ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Yearly Sales - Last 5 Years
    |--------------------------------------------------------------------------
    */

    private function yearlySalesData(): array
    {
        $currentYear = now()->year;
        $startYear = $currentYear - 4;

        $rows = $this->paidOrders()
            ->selectRaw('YEAR(created_at) as sale_year, SUM(total) as total')
            ->whereBetween('created_at', [
                Carbon::create($startYear, 1, 1)->startOfYear(),
                Carbon::create($currentYear, 12, 31)->endOfYear(),
            ])
            ->groupByRaw('YEAR(created_at)')
            ->orderBy('sale_year')
            ->get()
            ->keyBy('sale_year');

        $labels = [];
        $data = [];

        for ($year = $startYear; $year <= $currentYear; $year++) {
            $labels[] = (string) $year;
            $data[] = (int) ($rows[$year]->total ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
