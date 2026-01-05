<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingDashboardController extends Controller
{
    /**
     * Show tracking dashboard
     */
    public function index()
    {
        // Get real-time statistics
        $stats = $this->getTrackingStats();

        // Get recent orders with tracking
        $recentOrders = Order::with(['driver', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get active drivers
        $activeDrivers = User::where('is_driver', true)
            ->where('is_available', true)
            ->withCount([
                'driverOrders as active_orders_count' => function ($query) {
                    $query->whereIn('status', ['diproses', 'dikirim']);
                }
            ])
            ->get();

        // Get timeline entries for recent activity
        $recentTimeline = OrderTimeline::with(['order', 'user'])
            ->orderBy('timestamp', 'desc')
            ->limit(20)
            ->get();

        // Get notification statistics
        $notificationStats = app(NotificationService::class)->getNotificationStats();

        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics();

        return view('admin.tracking.dashboard', compact(
            'stats',
            'recentOrders',
            'activeDrivers',
            'recentTimeline',
            'notificationStats',
            'performanceMetrics'
        ));
    }

    /**
     * Get real-time tracking statistics
     */
    public function getTrackingStats(): array
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        return [
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'yesterday_orders' => Order::whereDate('created_at', $yesterday)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'diproses')->count(),
            'shipping_orders' => Order::where('status', 'dikirim')->count(),
            'completed_orders' => Order::where('status', 'selesai')->count(),
            'cancelled_orders' => Order::where('status', 'dibatalkan')->count(),
            'active_drivers' => User::where('is_driver', true)->where('is_available', true)->count(),
            'total_drivers' => User::where('is_driver', true)->count(),
            'avg_delivery_time' => $this->getAverageDeliveryTime(),
            'on_time_delivery_rate' => $this->getOnTimeDeliveryRate(),
        ];
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $last7Days = now()->subDays(7);

        return [
            'orders_last_7_days' => Order::where('created_at', '>=', $last7Days)->count(),
            'revenue_last_7_days' => Order::where('created_at', '>=', $last7Days)
                ->where('status', 'selesai')
                ->sum('grand_total'),
            'avg_order_value' => Order::where('status', 'selesai')->avg('grand_total'),
            'customer_satisfaction' => Order::whereNotNull('customer_rating')->avg('customer_rating'),
            'cancellation_rate' => $this->getCancellationRate(),
            'driver_performance' => $this->getDriverPerformance(),
        ];
    }

    /**
     * Get average delivery time
     */
    private function getAverageDeliveryTime(): float
    {
        $completedOrders = Order::where('status', 'selesai')
            ->whereNotNull('delivered_at')
            ->get();

        if ($completedOrders->isEmpty()) {
            return 0;
        }

        $totalMinutes = $completedOrders->sum(function ($order) {
            return $order->created_at->diffInMinutes($order->delivered_at);
        });

        return round($totalMinutes / $completedOrders->count(), 2);
    }

    /**
     * Get on-time delivery rate
     */
    private function getOnTimeDeliveryRate(): float
    {
        $completedOrders = Order::where('status', 'selesai')
            ->whereNotNull('delivered_at')
            ->whereNotNull('estimated_delivery_at')
            ->get();

        if ($completedOrders->isEmpty()) {
            return 0;
        }

        $onTimeOrders = $completedOrders->filter(function ($order) {
            return $order->delivered_at <= $order->estimated_delivery_at;
        });

        return round(($onTimeOrders->count() / $completedOrders->count()) * 100, 2);
    }

    /**
     * Get cancellation rate
     */
    private function getCancellationRate(): float
    {
        $totalOrders = Order::count();
        $cancelledOrders = Order::where('status', 'dibatalkan')->count();

        if ($totalOrders === 0) {
            return 0;
        }

        return round(($cancelledOrders / $totalOrders) * 100, 2);
    }

    /**
     * Get driver performance
     */
    private function getDriverPerformance(): array
    {
        return User::where('is_driver', true)
            ->withCount([
                'driverOrders as total_orders' => function ($query) {
                    $query->where('status', 'selesai');
                }
            ])
            ->withCount([
                'driverOrders as on_time_orders' => function ($query) {
                    $query->where('status', 'selesai')
                        ->whereColumn('delivered_at', '<=', 'estimated_delivery_at');
                }
            ])
            ->withAvg('driverOrders as avg_rating', 'customer_rating')
            ->orderBy('total_orders', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($driver) {
                $onTimeRate = $driver->total_orders > 0
                    ? round(($driver->on_time_orders / $driver->total_orders) * 100, 2)
                    : 0;

                return [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'total_orders' => $driver->total_orders,
                    'on_time_rate' => $onTimeRate,
                    'avg_rating' => round($driver->avg_rating ?? 0, 2),
                    'is_available' => $driver->is_available,
                ];
            })
            ->toArray();
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,diproses,dikirim,selesai,dibatalkan',
            'notes' => 'nullable|string|max:500',
            'driver_id' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $order->status;

        $updateData = [
            'status' => $request->status,
            'last_status_update' => now(),
        ];

        if ($request->driver_id) {
            $updateData['driver_id'] = $request->driver_id;
            $updateData['assigned_at'] = now();
        }

        // Set specific timestamps based on status
        switch ($request->status) {
            case 'diproses':
                $updateData['preparation_started_at'] = now();
                break;
            case 'dikirim':
                $updateData['out_for_delivery_at'] = now();
                $updateData['picked_up_at'] = now();
                break;
            case 'selesai':
                $updateData['delivered_at'] = now();
                break;
            case 'dibatalkan':
                $updateData['cancelled_at'] = now();
                $updateData['is_cancellable'] = false;
                break;
        }

        $order->update($updateData);

        // Create timeline entry
        OrderTimeline::createStatusEntry(
            $order,
            $request->status,
            ['notes' => $request->notes, 'admin_updated' => true],
            'admin',
            auth()->user()
        );

        // Send notifications
        $notificationService = app(NotificationService::class);
        $notificationService->sendStatusNotifications($order, $oldStatus, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order->fresh()
        ]);
    }

    /**
     * Get order timeline
     */
    public function getOrderTimeline(Order $order)
    {
        $timeline = $order->timeline()
            ->with('user')
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'timeline' => $timeline->map(function ($item) {
                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'title' => $item->title,
                    'description' => $item->description,
                    'icon' => $item->icon,
                    'color' => $item->color,
                    'timestamp' => $item->timestamp,
                    'metadata' => $item->metadata,
                    'triggered_by' => $item->triggered_by,
                    'user' => $item->user ? [
                        'name' => $item->user->name,
                        'is_admin' => $item->user->isAdmin(),
                        'is_driver' => $item->user->isDriver(),
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotifications(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'type' => 'required|string|in:email,sms,whatsapp,push',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();
        $notificationService = app(NotificationService::class);

        $results = $notificationService->sendBulkNotifications(
            $orders,
            $request->type,
            'bulk_notification',
            $request->title,
            $request->message
        );

        $successCount = count(array_filter($results));
        $totalCount = count($results);

        return response()->json([
            'success' => true,
            'message' => "Sent {$successCount} out of {$totalCount} notifications",
            'results' => $results
        ]);
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats()
    {
        $stats = app(NotificationService::class)->getNotificationStats();

        return response()->json($stats);
    }

    /**
     * Process pending notifications
     */
    public function processNotifications()
    {
        $notificationService = app(NotificationService::class);
        $processed = $notificationService->processPendingNotifications();

        return response()->json([
            'success' => true,
            'message' => "Processed {$processed} notifications",
            'processed' => $processed
        ]);
    }

    /**
     * Get real-time updates for dashboard
     */
    public function getRealTimeUpdates()
    {
        $stats = $this->getTrackingStats();
        $recentOrders = Order::with(['driver'])
            ->orderBy('last_status_update', 'desc')
            ->limit(5)
            ->get();

        $recentTimeline = OrderTimeline::with(['order', 'user'])
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();

        // Get notification statistics
        $notificationStats = app(NotificationService::class)->getNotificationStats();

        return response()->json([
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'recent_timeline' => $recentTimeline,
            'notification_stats' => $notificationStats,
            'timestamp' => now()->toISOString()
        ]);
    }
}
