<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->string('range', 'week')->toString(); // day|week|month
        $today = now()->toDateString();
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $revenueToday = Order::whereDate('created_at', $today)->sum('grand_total');
        $totalProducts = Product::where('is_active', true)->count();
        $totalUsers = User::count();
        $totalReviews = Review::count();
        $pending = Order::whereIn('status', ['pending', 'diproses'])->count();

        // Series depending on range
        $labels = collect();
        $sales = collect();
        if ($range === 'month') {
            // last 6 months
            foreach (range(5, 0) as $i) {
                $start = now()->startOfMonth()->subMonths($i);
                $end = (clone $start)->endOfMonth();
                $labels->push($start->format('M Y'));
                $sales->push((int) Order::whereBetween('created_at', [$start, $end])->sum('grand_total'));
            }
        } elseif ($range === 'day') {
            // last 7 days
            foreach (range(6, 0) as $i) {
                $date = now()->subDays($i);
                $labels->push($date->format('d M'));
                $sales->push((int) Order::whereDate('created_at', $date->toDateString())->sum('grand_total'));
            }
        } else { // week default
            // last 8 weeks
            foreach (range(7, 0) as $i) {
                $start = now()->startOfWeek()->subWeeks($i);
                $end = (clone $start)->endOfWeek();
                $labels->push('Minggu ' . $start->format('W'));
                $sales->push((int) Order::whereBetween('created_at', [$start, $end])->sum('grand_total'));
            }
        }

        // Category distribution by order items
        $categoryCounts = Category::select('categories.name', DB::raw('COALESCE(SUM(order_items.quantity),0) as total'))
            ->leftJoin('products', 'products.category_id', '=', 'categories.id')
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();
        $categoryLabels = $categoryCounts->pluck('name');
        $categoryValues = $categoryCounts->pluck('total')->map(fn($v) => (int) $v);

        // Top 5 products
        $topProducts = Product::select('products.*', DB::raw('COALESCE(SUM(order_items.quantity),0) as sold'))
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        $latestOrders = Order::latest()->limit(5)->get();

        // Convert data to JSON format for JavaScript
        $labelsJson = json_encode($labels);
        $salesJson = json_encode($sales);
        $categoryLabelsJson = json_encode($categoryLabels);
        $categoryValuesJson = json_encode($categoryValues);

        return view('admin.dashboard-luxury', compact(
            'ordersToday',
            'revenueToday',
            'totalProducts',
            'totalUsers',
            'pending',
            'totalReviews',
            'labels',
            'sales',
            'categoryLabels',
            'categoryValues',
            'topProducts',
            'latestOrders',
            'range',
            'labelsJson',
            'salesJson',
            'categoryLabelsJson',
            'categoryValuesJson'
        ));
    }

    public function exportPdf(Request $request)
    {
        $today = now()->toDateString();
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $revenueToday = Order::whereDate('created_at', $today)->sum('grand_total');

        // Revenue Weekly
        $startOfWeek = now()->startOfWeek();
        $revenueWeekly = Order::where('created_at', '>=', $startOfWeek)->sum('grand_total');
        $ordersWeekly = Order::where('created_at', '>=', $startOfWeek)->count();

        // Revenue Monthly
        $startOfMonth = now()->startOfMonth();
        $revenueMonthly = Order::where('created_at', '>=', $startOfMonth)->sum('grand_total');
        $ordersMonthly = Order::where('created_at', '>=', $startOfMonth)->count();

        $totalOrders = Order::count();
        $totalRevenue = Order::sum('grand_total');
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalReviews = Review::count();

        // Top 5 products
        $topProducts = Product::select('products.*', DB::raw('COALESCE(SUM(order_items.quantity),0) as sold'))
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        // Recent Orders
        $latestOrders = Order::latest()->limit(5)->get();

        // Orders by Status
        $statusDistribution = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $data = [
            'reportDate' => now()->format('d F Y H:i'),
            'ordersToday' => $ordersToday,
            'revenueToday' => $revenueToday,
            'ordersWeekly' => $ordersWeekly,
            'revenueWeekly' => $revenueWeekly,
            'ordersMonthly' => $ordersMonthly,
            'revenueMonthly' => $revenueMonthly,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalReviews' => $totalReviews,
            'topProducts' => $topProducts,
            'latestOrders' => $latestOrders,
            'statusDistribution' => $statusDistribution
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.dashboard-pdf', $data);
        return $pdf->download('Laporan_Dashboard_Sakura_' . now()->format('Ymd_His') . '.pdf');
    }
}
