<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\Notification;
use App\Models\User;
use App\Models\ShippingSetting;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Display driver dashboard
     */
    public function dashboard()
    {
        $driver = Auth::user();
        
        // Get driver's orders statistics
        $totalOrders = Order::where('driver_id', $driver->id)->count();
        $pendingOrders = Order::where('driver_id', $driver->id)
            ->where('status', 'dikirim')
            ->count();
        $completedOrders = Order::where('driver_id', $driver->id)
            ->where('status', 'selesai')
            ->count();
        $todayOrders = Order::where('driver_id', $driver->id)
            ->whereDate('created_at', today())
            ->count();
        
        // Get recent orders
        $recentOrders = Order::where('driver_id', $driver->id)
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get active orders (dikirim status)
        $activeOrders = Order::where('driver_id', $driver->id)
            ->where('status', 'dikirim')
            ->with(['user', 'orderItems.product'])
            ->orderBy('picked_up_at', 'asc')
            ->get();
        
        // Calculate total earnings (completed orders)
        $totalEarnings = Order::where('driver_id', $driver->id)
            ->where('status', 'selesai')
            ->sum('shipping_fee');
        
        // Get monthly earnings
        $monthlyEarnings = Order::where('driver_id', $driver->id)
            ->where('status', 'selesai')
            ->whereMonth('delivered_at', now()->month)
            ->whereYear('delivered_at', now()->year)
            ->sum('shipping_fee');
        
        return view('driver.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'todayOrders',
            'recentOrders',
            'activeOrders',
            'totalEarnings',
            'monthlyEarnings'
        ));
    }
    
    /**
     * Display list of driver's orders
     */
    public function orders(Request $request)
    {
        $driver = Auth::user();
        
        $query = Order::where('driver_id', $driver->id)
            ->with(['user', 'orderItems.product']);
        
        // Search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('address_line', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        $status = $request->get('status');
        if ($status) {
            $query->where('status', $status);
        }
        
        // Date filter
        $dateFilter = $request->get('date');
        if ($dateFilter) {
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'highest':
                $query->orderBy('grand_total', 'desc');
                break;
            case 'lowest':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $orders = $query->paginate(15)->withQueryString();
        
        $filters = [
            'search' => $search,
            'status' => $status,
            'date' => $dateFilter,
            'sort' => $sort
        ];
        
        return view('driver.orders.index', compact('orders', 'filters'));
    }
    
    /**
     * Display order details
     */
    public function showOrder(Order $order)
    {
        $driver = Auth::user();
        
        // Ensure order belongs to this driver
        if ($order->driver_id !== $driver->id) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }
        
        $order->load(['user', 'orderItems.product', 'timeline', 'communications']);
        
        // Get store location from settings or config
        $storeLat = ShippingSetting::getValue('store_lat', config('app.store_lat', -0.947100));
        $storeLng = ShippingSetting::getValue('store_lng', config('app.store_lng', 100.417200));
        
        return view('driver.orders.show', compact('order', 'storeLat', 'storeLng'));
    }
    
    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $driver = Auth::user();
        
        // Ensure order belongs to this driver
        if ($order->driver_id !== $driver->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke order ini.'
            ], 403);
        }
        
        $request->validate([
            'status' => 'required|string|in:diproses,dikirim,selesai',
            'notes' => 'nullable|string|max:500',
            'delivery_photo' => 'nullable|image|max:2048',
        ]);
        
        $oldStatus = $order->status;
        
        $updateData = [
            'status' => $request->status,
            'last_status_update' => now(),
        ];
        
        // Handle delivery photo
        if ($request->hasFile('delivery_photo')) {
            $photoPath = $request->file('delivery_photo')->store('delivery-photos', 'public');
            $updateData['delivery_photo'] = $photoPath;
        }
        
        // Set specific timestamps based on status
        switch ($request->status) {
            case 'dikirim':
                if (!$order->picked_up_at) {
                    $updateData['picked_up_at'] = now();
                }
                break;
            case 'selesai':
                $updateData['delivered_at'] = now();
                if ($request->notes) {
                    $updateData['delivery_notes'] = $request->notes;
                }
                break;
        }
        
        $order->update($updateData);
        
        // Create timeline entry
        OrderTimeline::createStatusEntry(
            $order,
            $request->status,
            [
                'notes' => $request->notes,
                'driver_updated' => true,
                'photo_path' => $updateData['delivery_photo'] ?? null
            ],
            'driver',
            $driver
        );
        
        // Send notifications to admin if status changed
        if ($oldStatus !== $request->status) {
            $this->notifyAdminsAboutStatusChange($order, $oldStatus, $request->status, $driver);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status order berhasil diperbarui',
                'order' => $order->fresh()
            ]);
        }
        
        return back()->with('success', 'Status order berhasil diperbarui');
    }
    
    /**
     * Get active orders for driver (for real-time updates)
     */
    public function getActiveOrders()
    {
        $driver = Auth::user();
        
        $activeOrders = Order::where('driver_id', $driver->id)
            ->where('status', 'dikirim')
            ->with(['user', 'orderItems.product'])
            ->orderBy('picked_up_at', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'orders' => $activeOrders
        ]);
    }
    
    /**
     * Update driver location
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        
        $driver = Auth::user();
        $driver->updateLocation($request->latitude, $request->longitude);
        
        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui',
            'location' => [
                'latitude' => $driver->current_latitude,
                'longitude' => $driver->current_longitude,
                'updated_at' => $driver->last_location_update
            ]
        ]);
    }
    
    /**
     * Notify all admins about order status change by driver
     */
    private function notifyAdminsAboutStatusChange(Order $order, string $oldStatus, string $newStatus, User $driver): void
    {
        // Get all admin users
        $admins = User::whereHas('roles', function($query) {
            $query->where('slug', 'admin');
        })->get();
        
        // If no admin found by role, try by is_admin flag
        if ($admins->isEmpty()) {
            $admins = User::where('is_admin', true)->get();
        }
        
        // Status messages
        $statusMessages = [
            'diproses' => 'sedang diproses',
            'dikirim' => 'sedang dikirim',
            'selesai' => 'telah selesai dan diterima',
        ];
        
        $statusMessage = $statusMessages[$newStatus] ?? $newStatus;
        
        // Prepare notification data
        $title = "Update Status Pesanan - {$order->order_code}";
        $message = "Driver {$driver->name} telah mengubah status pesanan {$order->order_code} dari '{$oldStatus}' menjadi '{$newStatus}'. Pesanan sekarang {$statusMessage}.";
        
        $notificationData = [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'driver_id' => $driver->id,
            'driver_name' => $driver->name,
            'updated_at' => now()->toDateTimeString(),
        ];
        
        // Send notification to each admin
        foreach ($admins as $admin) {
            // Send Laravel notification (database + email)
            try {
                $admin->notify(new CustomNotification(
                    $title,
                    $message,
                    $notificationData
                ));
            } catch (\Exception $e) {
                \Log::error("Failed to send notification to admin {$admin->id}: " . $e->getMessage());
            }
            
            // Also create notification record in database
            try {
                Notification::create([
                    'order_id' => $order->id,
                    'user_id' => $admin->id,
                    'type' => 'in_app',
                    'channel' => 'order_status_update',
                    'title' => $title,
                    'message' => $message,
                    'data' => $notificationData,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error("Failed to create notification record for admin {$admin->id}: " . $e->getMessage());
            }
        }
        
        \Log::info("Notified {$admins->count()} admin(s) about order status change", [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'driver_id' => $driver->id,
        ]);
    }
}

