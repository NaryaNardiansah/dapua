<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\Notification;
use App\Models\OrderChat;
use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class TrackingController extends Controller
{
    /**
     * Show enhanced tracking page for customer
     */
    public function show($trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)
            ->with([
                'orderItems.product',
                'user',
                'driver',
                'timeline' => function ($query) {
                    $query->where('is_visible_to_customer', true)
                        ->orderBy('timestamp', 'desc');
                },
                'chats' => function ($query) {
                    $query->orderBy('created_at', 'desc')->limit(20);
                }
            ])
            ->firstOrFail();

        // Get timeline entries
        $timeline = $order->timeline ?? collect();

        // Calculate ETA
        $eta = $this->calculateETA($order);

        // Get weather data if available
        $weather = $order->weather_data ?? null;

        // Get performance metrics
        $metrics = $this->getPerformanceMetrics($order);

        // Calculate progress percentage based on order status
        $progressPercentage = $this->calculateProgressPercentage($order);

        // Get recent chat messages
        $recentChats = $order->chats ?? collect();

        // Get driver's current location
        $driverLocation = null;
        if ($order->driver_id) {
            $driverLocation = DriverLocation::getLatestLocation($order->driver);
        }

        // Check if order needs payment
        $snapToken = null;
        $clientKey = null;
        if ((strtolower($order->payment_status) == 'unpaid' || strtolower($order->payment_status) == 'pending') && $order->status != 'dibatalkan' && strtolower($order->payment_method) != 'cod') {
            try {
                // Configure Midtrans
                MidtransConfig::$serverKey = config('services.midtrans.server_key');
                MidtransConfig::$isProduction = (bool) config('services.midtrans.is_production');
                MidtransConfig::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $order->id . '-' . time(),
                        'gross_amount' => (int) $order->grand_total,
                    ],
                    'customer_details' => [
                        'first_name' => $order->recipient_name,
                        'phone' => $order->recipient_phone,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $clientKey = config('services.midtrans.client_key');
            } catch (\Exception $e) {
                Log::error('Failed to generate Snap token for tracking page: ' . $e->getMessage());
            }
        }

        return view('tracking.show', compact('order', 'timeline', 'eta', 'weather', 'metrics', 'recentChats', 'driverLocation', 'progressPercentage', 'snapToken', 'clientKey'));
    }

    /**
     * Enhanced API endpoint for real-time tracking
     */
    public function api($trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)
            ->with([
                'driver',
                'timeline' => function ($query) {
                    $query->where('is_visible_to_customer', true)
                        ->orderBy('timestamp', 'desc');
                }
            ])
            ->firstOrFail();

        $eta = $this->calculateETA($order);
        $timeline = $order->timeline ?? collect();

        return response()->json([
            'order_code' => $order->order_code,
            'status' => $order->status,
            'status_text' => ucfirst($order->status),
            'progress_percentage' => $this->getProgressPercentage($order->status),
            'eta' => $eta,
            'driver' => $order->driver ? [
                'name' => $order->driver->name,
                'phone' => $order->driver->phone ?? null,
                'photo' => $order->driver->photo ?? null,
                'vehicle' => $order->driver->vehicle_type . ' ' . $order->driver->vehicle_number,
                'location' => $order->driver->current_latitude && $order->driver->current_longitude ? [
                    'lat' => $order->driver->current_latitude,
                    'lng' => $order->driver->current_longitude,
                    'updated_at' => $order->driver->last_location_update
                ] : null,
                'is_available' => $order->driver->is_available
            ] : null,
            'timeline' => $timeline->map(function ($item) {
                return [
                    'status' => $item->status,
                    'title' => $item->title,
                    'description' => $item->description,
                    'icon' => $item->icon,
                    'color' => $item->color,
                    'timestamp' => $item->timestamp,
                    'metadata' => $item->metadata,
                    'triggered_by' => $item->triggered_by,
                ];
            }),
            'delivery_info' => [
                'recipient_name' => $order->recipient_name,
                'recipient_phone' => $order->recipient_phone,
                'address' => $order->address_line,
                'coordinates' => $order->latitude && $order->longitude ? [
                    'lat' => $order->latitude,
                    'lng' => $order->longitude
                ] : null,
                'instructions' => $order->delivery_instructions,
                'special_requests' => $order->special_requests,
            ],
            'communication' => [
                'whatsapp_link' => $this->generateWhatsAppLink($order),
                'can_cancel' => $order->is_cancellable && in_array($order->status, ['pending', 'diproses']),
                'can_modify' => $order->status === 'pending',
            ],
            'weather' => $order->weather_data,
            'performance' => $this->getPerformanceMetrics($order),
            'sharing' => [
                'qr_code' => $order->qr_code,
                'share_token' => $order->share_token,
                'share_url' => route('tracking.show', $trackingCode),
            ]
        ]);
    }

    /**
     * Update order status (for admin/driver)
     */
    public function updateStatus(Request $request, $trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)->firstOrFail();

        $request->validate([
            'status' => 'required|string|in:pending,diproses,dikirim,selesai,dibatalkan',
            'notes' => 'nullable|string|max:500',
            'metadata' => 'nullable|array'
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status,
            'last_status_update' => now(),
        ]);

        // Create timeline entry
        OrderTimeline::createStatusEntry(
            $order,
            $request->status,
            $request->metadata ?? [],
            Auth::check() ? (Auth::user()->isAdmin() ? 'admin' : (Auth::user()->isDriver() ? 'driver' : 'customer')) : 'system',
            Auth::user()
        );

        // Send notifications
        $this->sendStatusNotifications($order, $oldStatus, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'order' => $order->fresh()
        ]);
    }

    /**
     * Customer feedback form
     */
    public function feedback(Request $request, $trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)
            ->where('status', 'selesai')
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500'
        ]);

        $order->update([
            'customer_rating' => $request->rating,
            'customer_feedback' => $request->feedback,
        ]);

        // Create timeline entry
        OrderTimeline::createStatusEntry(
            $order,
            'feedback_received',
            ['rating' => $request->rating, 'feedback' => $request->feedback],
            'customer',
            Auth::user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas feedback Anda!'
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)->firstOrFail();

        if (!$order->is_cancellable) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan'
            ], 400);
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $order->update([
            'status' => 'dibatalkan',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason,
            'is_cancellable' => false,
        ]);

        // Create timeline entry
        OrderTimeline::createStatusEntry(
            $order,
            'cancelled',
            ['reason' => $request->reason],
            'customer',
            Auth::user()
        );

        // Send cancellation notification
        $this->sendCancellationNotification($order);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibatalkan'
        ]);
    }

    /**
     * Share order tracking
     */
    public function share($trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)->firstOrFail();

        // Generate share token if not exists
        if (!$order->share_token) {
            $order->update([
                'share_token' => \Str::random(32)
            ]);
        }

        return response()->json([
            'share_url' => route('tracking.show', $trackingCode),
            'share_token' => $order->share_token,
            'qr_code' => $order->qr_code,
        ]);
    }

    /**
     * Calculate ETA for order
     */
    private function calculateETA(Order $order): ?array
    {
        if (!$order->estimated_delivery_time) {
            return null;
        }

        $now = now();
        $eta = \Carbon\Carbon::parse($order->estimated_delivery_time);

        return [
            'estimated_time' => $eta->format('H:i'),
            'estimated_date' => $eta->format('d M Y'),
            'minutes_remaining' => max(0, $now->diffInMinutes($eta, false)),
            'is_delayed' => $eta->isPast(),
            'delay_minutes' => $eta->isPast() ? $now->diffInMinutes($eta) : 0,
        ];
    }

    /**
     * Get progress percentage based on status
     */
    private function getProgressPercentage(string $status): int
    {
        $progress = [
            'pending' => 10,
            'diproses' => 30,
            'dikirim' => 70,
            'selesai' => 100,
            'dibatalkan' => 0,
        ];

        return $progress[$status] ?? 0;
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(Order $order): array
    {
        return [
            'preparation_time' => $order->total_preparation_time_minutes,
            'delivery_time' => $order->total_delivery_time_minutes,
            'total_time' => ($order->total_preparation_time_minutes ?? 0) + ($order->total_delivery_time_minutes ?? 0),
            'delay_minutes' => $order->delay_minutes ?? 0,
            'performance_score' => $this->calculatePerformanceScore($order),
        ];
    }

    /**
     * Calculate performance score
     */
    private function calculatePerformanceScore(Order $order): int
    {
        $score = 100;

        // Deduct points for delays
        if ($order->delay_minutes > 0) {
            $score -= min(30, $order->delay_minutes);
        }

        // Deduct points for long preparation time
        if ($order->total_preparation_time_minutes > 30) {
            $score -= min(20, ($order->total_preparation_time_minutes - 30) * 2);
        }

        return max(0, $score);
    }

    /**
     * Generate WhatsApp link
     */
    private function generateWhatsAppLink(Order $order): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $order->recipient_phone);
        if (!str_starts_with($phoneNumber, '62')) {
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } else {
                $phoneNumber = '62' . $phoneNumber;
            }
        }

        $message = "Halo {$order->recipient_name}, ini dari Dapur Sakura. Status pesanan #{$order->id}: " . ucfirst($order->status) . ". Apakah ada yang bisa kami bantu?";

        return "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
    }

    /**
     * Send status notifications
     */
    private function sendStatusNotifications(Order $order, string $oldStatus, string $newStatus): void
    {
        $templates = Notification::getTemplates();

        if (isset($templates[$newStatus])) {
            foreach ($templates[$newStatus] as $type => $template) {
                $message = str_replace(
                    ['{order_code}', '{customer_name}', '{estimated_time}', '{driver_name}'],
                    [$order->id, $order->recipient_name, $order->estimated_delivery_time, $order->driver?->name ?? 'Driver'],
                    $template['message']
                );

                Notification::createOrderNotification(
                    $order,
                    $type,
                    'order_update',
                    $template['title'],
                    $message
                );
            }
        }
    }

    /**
     * Send cancellation notification
     */
    private function sendCancellationNotification(Order $order): void
    {
        $message = "Halo {$order->recipient_name}, pesanan #{$order->id} Anda telah dibatalkan. Alasan: {$order->cancellation_reason}";

        Notification::createOrderNotification(
            $order,
            'whatsapp',
            'order_cancelled',
            'Pesanan Dibatalkan',
            $message
        );
    }

    /**
     * Calculate progress percentage based on order status
     */
    private function calculateProgressPercentage(Order $order): int
    {
        $statusProgress = [
            'pending' => 20,
            'diproses' => 50,
            'dikirim' => 80,
            'selesai' => 100,
            'dibatalkan' => 0,
        ];

        $baseProgress = $statusProgress[$order->status] ?? 0;

        // Add bonus progress based on timeline entries
        $timelineCount = $order->timeline ? $order->timeline->count() : 0;
        $bonusProgress = min($timelineCount * 5, 20); // Max 20% bonus

        return min($baseProgress + $bonusProgress, 100);
    }
}

