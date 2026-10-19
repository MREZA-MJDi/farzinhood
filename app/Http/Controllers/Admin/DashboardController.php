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
use App\Support\JalaliCalendar;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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

        // Reporting periods follow the Jalali calendar shown throughout the admin UI.
        [$jalaliYear, $jalaliMonth] = JalaliCalendar::fromGregorian(now());

        $monthStart = JalaliCalendar::toGregorian($jalaliYear, $jalaliMonth)->startOfDay();
        [$nextMonthYear, $nextMonthNumber] = $jalaliMonth === 12
            ? [$jalaliYear + 1, 1]
            : [$jalaliYear, $jalaliMonth + 1];
        $nextMonth = JalaliCalendar::toGregorian($nextMonthYear, $nextMonthNumber)->startOfDay();

        $yearStart = JalaliCalendar::toGregorian($jalaliYear, 1)->startOfDay();
        $nextYear = JalaliCalendar::toGregorian($jalaliYear + 1, 1)->startOfDay();


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

        [$previousJalaliYear, $previousJalaliMonth] = $jalaliMonth === 1
            ? [$jalaliYear - 1, 12]
            : [$jalaliYear, $jalaliMonth - 1];

        $previousMonthStart = JalaliCalendar::toGregorian(
            $previousJalaliYear,
            $previousJalaliMonth
        )->startOfDay();

        $previousMonthEnd = $monthStart->copy()->subMicrosecond();

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

            $labels[] = JalaliCalendar::format(
                Carbon::parse($key, config('app.timezone')),
                'day-month'
            );

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
        [$currentJalaliYear] = JalaliCalendar::fromGregorian(now());

        $start = JalaliCalendar::toGregorian($currentJalaliYear, 1)->startOfDay();
        $end = JalaliCalendar::toGregorian($currentJalaliYear + 1, 1)->startOfDay();

        // Group daily rows in SQL for an indexed range query, then bucket by
        // the Jalali month in PHP (Gregorian MONTH() is wrong for this chart).
        $rows = $this->paidOrders()
            ->selectRaw('DATE(created_at) as sale_date, SUM(total) as total')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->groupByRaw('DATE(created_at)')
            ->orderBy('sale_date')
            ->get();

        $monthlyTotals = array_fill(1, 12, 0);

        foreach ($rows as $row) {
            $date = Carbon::parse($row->sale_date, config('app.timezone'));
            [, $month] = JalaliCalendar::fromGregorian($date);
            $monthlyTotals[$month] += (int) $row->total;
        }

        return [
            'labels' => JalaliCalendar::MONTHS,
            'data' => array_values($monthlyTotals),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Yearly Sales - Last 5 Jalali Years
    |--------------------------------------------------------------------------
    */

    private function yearlySalesData(): array
    {
        [$currentJalaliYear] = JalaliCalendar::fromGregorian(now());
        $startYear = $currentJalaliYear - 4;

        $start = JalaliCalendar::toGregorian($startYear, 1)->startOfDay();
        $end = JalaliCalendar::toGregorian($currentJalaliYear + 1, 1)->startOfDay();

        $rows = $this->paidOrders()
            ->selectRaw('DATE(created_at) as sale_date, SUM(total) as total')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->groupByRaw('DATE(created_at)')
            ->orderBy('sale_date')
            ->get();

        $yearlyTotals = array_fill($startYear, 5, 0);

        foreach ($rows as $row) {
            $date = Carbon::parse($row->sale_date, config('app.timezone'));
            [$jalaliYear] = JalaliCalendar::fromGregorian($date);

            if (array_key_exists($jalaliYear, $yearlyTotals)) {
                $yearlyTotals[$jalaliYear] += (int) $row->total;
            }
        }

        $labels = [];
        $data = [];

        for ($year = $startYear; $year <= $currentJalaliYear; $year++) {
            $labels[] = JalaliCalendar::digits($year);
            $data[] = (int) ($yearlyTotals[$year] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

}
