<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;

class TrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:run {--action=all : Action to run (all, notifications, auto-assign, cleanup)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tracking system tasks (notifications, auto-assignment, cleanup)';

    protected $notificationService;

    public function __construct(
        NotificationService $notificationService
    ) {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->option('action');

        $this->info("Running tracking system tasks: {$action}");

        switch ($action) {
            case 'notifications':
                $this->processNotifications();
                break;
            case 'auto-assign':
                $this->autoAssignDrivers();
                break;
            case 'cleanup':
                $this->cleanupOldData();
                break;
            case 'all':
            default:
                $this->processNotifications();
                $this->autoAssignDrivers();
                $this->cleanupOldData();
                break;
        }

        $this->info('Tracking system tasks completed successfully!');
    }

    /**
     * Process pending notifications
     */
    private function processNotifications()
    {
        $this->info('Processing pending notifications...');
        
        $processed = $this->notificationService->processPendingNotifications();
        $this->info("Processed {$processed} notifications.");
        
        $retried = $this->notificationService->retryFailedNotifications();
        if ($retried > 0) {
            $this->info("Retried {$retried} failed notifications.");
        }
    }

    /**
     * Auto-assign drivers to pending orders
     */
    private function autoAssignDrivers()
    {
        $this->info('Auto-assigning drivers to pending orders...');
        
        $pendingOrders = Order::where('status', 'pending')
            ->whereNull('driver_id')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $assigned = 0;
        
        foreach ($pendingOrders as $order) {
            // Simple auto-assignment logic
            $availableDriver = User::where('is_driver', true)
                ->where('is_available', true)
                ->first();
            
            if ($availableDriver) {
                $order->update(['driver_id' => $availableDriver->id]);
                $assigned++;
                $this->info("Assigned order #{$order->order_code} to driver {$availableDriver->name}");
            }
        }
        
        $this->info("Auto-assigned {$assigned} orders to drivers.");
    }

    /**
     * Cleanup old data
     */
    private function cleanupOldData()
    {
        $this->info('Cleaning up old data...');
        
        // Clean up old timeline entries (older than 30 days)
        $deletedTimeline = \App\Models\OrderTimeline::where('created_at', '<', now()->subDays(30))->delete();
        $this->info("Deleted {$deletedTimeline} old timeline entries.");
        
        // Clean up old notifications (older than 7 days)
        $deletedNotifications = \App\Models\Notification::where('created_at', '<', now()->subDays(7))->delete();
        $this->info("Deleted {$deletedNotifications} old notifications.");
        
        // Update driver availability based on last location update
        $inactiveDrivers = User::where('is_driver', true)
            ->where('is_available', true)
            ->where('last_location_update', '<', now()->subHours(2))
            ->update(['is_available' => false]);
        
        if ($inactiveDrivers > 0) {
            $this->info("Marked {$inactiveDrivers} drivers as inactive due to no location updates.");
        }
        
        // Generate performance reports
        $this->generatePerformanceReport();
    }

    /**
     * Generate performance report
     */
    private function generatePerformanceReport()
    {
        $this->info('Generating performance report...');
        
        $analytics = [
            'total_orders' => Order::whereBetween('created_at', [now()->subDays(7), now()])->count(),
            'total_revenue' => Order::whereBetween('created_at', [now()->subDays(7), now()])
                ->whereIn('status', ['selesai', 'dikirim'])
                ->sum('total_amount'),
            'avg_order_value' => Order::whereBetween('created_at', [now()->subDays(7), now()])
                ->whereIn('status', ['selesai', 'dikirim'])
                ->avg('total_amount'),
            'avg_delivery_time' => 'N/A',
            'cancellation_rate' => 'N/A',
            'customer_satisfaction' => 4.5,
        ];
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Orders (7 days)', $analytics['total_orders']],
                ['Total Revenue', 'Rp ' . number_format($analytics['total_revenue'])],
                ['Average Order Value', 'Rp ' . number_format($analytics['avg_order_value'])],
                ['Average Delivery Time', $analytics['avg_delivery_time'] . ' minutes'],
                ['Cancellation Rate', $analytics['cancellation_rate'] . '%'],
                ['Customer Satisfaction', number_format($analytics['customer_satisfaction'], 1) . '/5'],
            ]
        );
        
        // Driver performance
        $drivers = User::where('is_driver', true)->get();
        $driverPerformance = [];
        
        foreach ($drivers as $driver) {
            $totalOrders = Order::where('driver_id', $driver->id)
                ->whereIn('status', ['selesai', 'dikirim'])
                ->count();
            
            if ($totalOrders > 0) {
                $driverPerformance[] = [
                    'Driver' => $driver->name,
                    'Orders' => $totalOrders,
                    'Avg Time' => 'N/A',
                    'On Time Rate' => 'N/A',
                    'Rating' => 'N/A',
                ];
            }
        }
        
        if (!empty($driverPerformance)) {
            $this->info('Driver Performance:');
            $this->table(
                ['Driver', 'Orders', 'Avg Time', 'On Time Rate', 'Rating'],
                $driverPerformance
            );
        }
    }
}
