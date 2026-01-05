<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderChat;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    /**
     * Get list of my orders
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Get order details for tracking page
     */
    public function getOrderDetails(Request $request, Order $order)
    {
        $order->load(['orderItems.product', 'driver', 'user']);

        return response()->json([
            'order' => $order,
            'items' => $order->orderItems,
            'driver' => $order->driver,
            'customer' => $order->user
        ]);
    }

    /**
     * Cancel order with reason
     */
    public function cancelOrder(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }

        DB::transaction(function () use ($order, $request) {
            $order->update([
                'status' => 'dibatalkan',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->reason,
                'is_cancellable' => false
            ]);

            // Add to status history
            $this->addStatusHistory($order, 'dibatalkan', 'Order cancelled by customer: ' . $request->reason);

            // Send notification to admin
            $this->sendCancellationNotification($order);
        });

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully'
        ]);
    }

    /**
     * Send message in order communication
     */
    public function sendMessage(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $communication = OrderChat::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'sender_type' => 'customer',
            'message_type' => 'text',
            'message' => $request->message,
            'is_read' => false
        ]);

        // Send notification to driver/admin
        $this->sendMessageNotification($order, $communication);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'communication' => $communication
        ]);
    }

    /**
     * Get order communications
     */
    public function getCommunications(Request $request, Order $order)
    {
        $communications = OrderChat::where('order_id', $order->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($comm) {
                return [
                    'id' => $comm->id,
                    'sender_type' => $comm->sender_type,
                    'sender_name' => $comm->user ? $comm->user->name : 'System',
                    'message_type' => $comm->message_type,
                    'formatted_message' => $comm->message,
                    'created_at' => $comm->created_at->toISOString(),
                    'is_read' => $comm->is_read
                ];
            });

        return response()->json([
            'data' => $communications
        ]);
    }

    /**
     * Mark communications as read
     */
    public function markAsRead(Request $request, Order $order)
    {
        OrderChat::where('order_id', $order->id)
            ->where('sender_type', '!=', 'customer')
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Update delivery instructions
     */
    public function updateDeliveryInstructions(Request $request, Order $order)
    {
        $request->validate([
            'instructions' => 'required|string|max:500'
        ]);

        $order->update([
            'delivery_instructions' => $request->instructions
        ]);

        // Notify driver
        $this->sendInstructionsNotification($order);

        return response()->json([
            'success' => true,
            'message' => 'Delivery instructions updated'
        ]);
    }

    /**
     * Reorder items from previous order
     */
    public function reorder(Request $request, Order $order)
    {
        $cart = $request->session()->get('cart', []);

        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->is_active) {
                $cart[$item->product_id] = ($cart[$item->product_id] ?? 0) + $item->quantity;
            }
        }

        $request->session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Items added to cart',
            'cart_count' => array_sum($cart)
        ]);
    }

    /**
     * Get real-time order status
     */
    public function getRealtimeStatus(Request $request, Order $order)
    {
        $order->load('driver');

        $statusText = match ($order->status) {
            'pending' => 'Menunggu Pembayaran',
            'diproses' => 'Sedang Diproses',
            'dikirim' => 'Sedang Dikirim',
            'selesai' => 'Telah Dikirim',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($order->status)
        };

        $driver = null;
        if ($order->driver) {
            $driver = [
                'id' => $order->driver->id,
                'name' => $order->driver->name,
                'phone' => $order->driver->phone,
                'vehicle_type' => $order->driver->vehicle_type,
                'vehicle_number' => $order->driver->vehicle_number,
                'location' => [
                    'lat' => $order->driver->current_latitude,
                    'lng' => $order->driver->current_longitude
                ],
                'last_update' => $order->driver->last_location_update
            ];
        }

        return response()->json([
            'status' => $order->status,
            'status_text' => $statusText,
            'driver' => $driver,
            'estimated_delivery' => $order->estimated_delivery_at,
            'last_update' => $order->last_status_update
        ]);
    }

    /**
     * Get order timeline
     */
    public function getTimeline(Request $request, Order $order)
    {
        $timeline = [];

        // Order created
        $timeline[] = [
            'title' => 'Pesanan Dibuat',
            'description' => 'Pesanan telah dibuat dan menunggu konfirmasi',
            'timestamp' => $order->created_at->toISOString(),
            'completed' => true,
            'current' => false
        ];

        // Payment confirmed
        if ($order->payment_status === 'paid') {
            $timeline[] = [
                'title' => 'Pembayaran Dikonfirmasi',
                'description' => 'Pembayaran telah diterima dan pesanan sedang diproses',
                'timestamp' => $order->updated_at->toISOString(),
                'completed' => true,
                'current' => false
            ];
        }

        // Assigned to driver
        if ($order->assigned_at) {
            $timeline[] = [
                'title' => 'Ditugaskan ke Kurir',
                'description' => $order->driver ? "Ditugaskan ke {$order->driver->name}" : 'Ditugaskan ke kurir',
                'timestamp' => $order->assigned_at->toISOString(),
                'completed' => true,
                'current' => false
            ];
        }

        // Picked up
        if ($order->picked_up_at) {
            $timeline[] = [
                'title' => 'Sedang Diambil',
                'description' => 'Kurir sedang mengambil pesanan dari toko',
                'timestamp' => $order->picked_up_at->toISOString(),
                'completed' => true,
                'current' => false
            ];
        }

        // In transit
        if ($order->status === 'dikirim') {
            $timeline[] = [
                'title' => 'Sedang Dikirim',
                'description' => 'Pesanan sedang dalam perjalanan ke lokasi Anda',
                'timestamp' => $order->updated_at->toISOString(),
                'completed' => false,
                'current' => true
            ];
        }

        // Delivered
        if ($order->delivered_at) {
            $timeline[] = [
                'title' => 'Telah Dikirim',
                'description' => 'Pesanan telah sampai di lokasi tujuan',
                'timestamp' => $order->delivered_at->toISOString(),
                'completed' => true,
                'current' => false
            ];
        }

        // Cancelled
        if ($order->status === 'dibatalkan') {
            $timeline[] = [
                'title' => 'Pesanan Dibatalkan',
                'description' => $order->cancellation_reason ?? 'Pesanan dibatalkan',
                'timestamp' => $order->cancelled_at->toISOString(),
                'completed' => true,
                'current' => false
            ];
        }

        return response()->json([
            'timeline' => $timeline
        ]);
    }

    /**
     * Share order on social media
     */
    public function shareOrder(Request $request, Order $order)
    {
        $request->validate([
            'platform' => 'required|in:whatsapp,facebook,twitter,email'
        ]);

        $trackingUrl = route('tracking.show', $order->tracking_code);
        $message = "Check out my order from Dapur Sakura! Order #{$order->order_code} - {$trackingUrl}";

        $shareUrl = match ($request->platform) {
            'whatsapp' => "https://wa.me/?text=" . urlencode($message),
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($trackingUrl),
            'twitter' => "https://twitter.com/intent/tweet?text=" . urlencode($message),
            'email' => "mailto:?subject=My Order from Dapur Sakura&body=" . urlencode($message)
        };

        return response()->json([
            'success' => true,
            'share_url' => $shareUrl
        ]);
    }

    /**
     * Download order invoice
     */
    public function downloadInvoice(Request $request, Order $order)
    {
        $order->load(['orderItems.product', 'user']);

        // Generate PDF invoice (you can use a package like dompdf or tcpdf)
        $html = view('orders.invoice', compact('order'))->render();

        // For now, return a simple text response
        // In production, you would generate a proper PDF
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="invoice-' . $order->order_code . '.html"');
    }

    /**
     * Add status history entry
     */
    private function addStatusHistory(Order $order, string $status, string $description)
    {
        $history = $order->status_history ?? [];
        $history[] = [
            'status' => $status,
            'description' => $description,
            'timestamp' => now()->toISOString(),
            'user_id' => Auth::id()
        ];

        $order->update(['status_history' => $history]);
    }

    /**
     * Send cancellation notification
     */
    private function sendCancellationNotification(Order $order)
    {
        // Send email to admin
        try {
            Mail::send('emails.order-cancelled', ['order' => $order], function ($message) use ($order) {
                $message->to(config('mail.admin_email', 'admin@dapursakura.com'))
                    ->subject('Order Cancelled - ' . $order->order_code);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation email: ' . $e->getMessage());
        }
    }

    /**
     * Send message notification
     */
    private function sendMessageNotification(Order $order, OrderChat $communication)
    {
        // Send notification to driver if assigned
        if ($order->driver) {
            // You can implement push notifications here
            \Log::info("New message for driver {$order->driver->name} on order {$order->order_code}");
        }
    }

    /**
     * Send instructions notification
     */
    private function sendInstructionsNotification(Order $order)
    {
        if ($order->driver) {
            // Send notification to driver about updated instructions
            \Log::info("Updated delivery instructions for driver {$order->driver->name} on order {$order->order_code}");
        }
    }
}

